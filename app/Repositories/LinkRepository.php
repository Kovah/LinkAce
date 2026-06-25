<?php

namespace App\Repositories;

use App\Enums\ModelAttribute;
use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Events\AuditCustom;

class LinkRepository
{
    /**
     * Create a new link from the given data. If there is no title and description
     * given, fill the data with meta pulled from the website itself. Also
     * fill other needed information like the user id and the link icon.
     * After that, dispatch the backup job for the Wayback Machine.
     *
     * @param array $data
     * @param bool  $flashAlerts
     * @return Link
     */
    public static function create(array $data, bool $flashAlerts = false): Link
    {
        $linkMeta = (new HtmlMeta)->getFromUrl($data['url'], $flashAlerts);

        $data['title'] ??= $linkMeta['title'];
        $data['description'] ??= $linkMeta['description'];
        $data['user_id'] = auth()->user()->id;
        $data['icon'] = LinkIconMapper::getIconForUrl($data['url']);
        $data['thumbnail'] = $linkMeta['thumbnail'];

        // If the meta helper was not successful, set the status to broken so it can be re-checked later
        if ($linkMeta['success'] === false) {
            $data['status'] = Link::STATUS_BROKEN;
        }

        $link = Link::withoutSyncingToSearch(fn () => Link::create($data));

        self::processLinkTaxonomies($link, $data);
        self::syncLinkToSearch($link);

        $link->initiateInternetArchiveBackup();

        return $link;
    }

    /**
     * Update a link with the given data and sync both the tags and lists.
     *
     * @param Link  $link
     * @param array $data
     * @return Link
     */
    public static function update(Link $link, array $data): Link
    {
        $data['icon'] = LinkIconMapper::getIconForUrl($data['url'] ?? $link->url);

        // If the URL changed and the link was broken, reset status so it gets re-checked
        if (isset($data['url']) && $data['url'] !== $link->url && $link->status === Link::STATUS_BROKEN) {
            $data['status'] = Link::STATUS_OK;
            $data['last_checked_at'] = null;
        }

        $updateData = array_intersect_key($data, array_flip($link->getFillable()));

        Link::withoutSyncingToSearch(fn () => $link->update($updateData));

        self::processLinkTaxonomies($link, $data);
        self::syncLinkToSearch($link);

        return $link;
    }

    public static function bulkUpdate(array $models, array $data): Collection
    {
        $links = Link::whereIn('id', $models)->with([
            'tags:id',
            'lists:id',
        ])->get();

        return $links->map(function (Link $link) use ($data) {
            if (!auth()->user()->can('update', $link)) {
                Log::warning('Could not update link ' . $link->id . ' during bulk update: Permission denied!');
                return null;
            }

            $linkData = [
                'tags' => $data['tags_mode'] === 'replace'
                    ? $data['tags']
                    : array_merge($link->tags->pluck('id')->toArray(), $data['tags']),
                'lists' => $data['lists_mode'] === 'replace'
                    ? $data['lists']
                    : array_merge($link->lists->pluck('id')->toArray(), $data['lists']),
            ];

            if (isset($data['visibility'])) {
                $linkData['visibility'] = $data['visibility'];
            }

            return self::update($link, $linkData);
        });
    }

    /**
     * Try to delete a given link. If it fails, log the error.
     *
     * @param Link $link
     * @return bool
     */
    public static function delete(Link $link): bool
    {
        try {
            $link->tags()->detach();
            $link->lists()->detach();
            Link::withoutSyncingToSearch(fn () => $link->delete());
            self::removeLinkFromSearch($link);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }

    protected static function syncLinkToSearch(Link $link): void
    {
        if (!self::searchIndexingEnabled()) {
            return;
        }

        $link->load(['tags', 'lists']);
        $link->searchable();
    }

    protected static function removeLinkFromSearch(Link $link): void
    {
        if (!self::searchIndexingEnabled()) {
            return;
        }

        $link->unsearchable();
    }

    protected static function searchIndexingEnabled(): bool
    {
        return in_array(
            config('linkace.search.driver'),
            config('linkace.search.external_drivers', []),
            true
        );
    }

    protected static function processLinkTaxonomies(Link $link, array $data): void
    {
        if (!empty($data['tags'])) {
            self::updateTagsForLink($link, $data['tags']);
        } else {
            // Only save a "removed" revision if there were tags before
            if ($link->tags()->count() > 0) {
                self::createRelationshipRevision(
                    $link,
                    Link::AUDIT_TAGS_NAME,
                    $link->tags->pluck('id')->toArray(),
                    null
                );
            }

            $link->tags()->detach();
        }

        if (!empty($data['lists'])) {
            self::updateListsForLink($link, $data['lists']);
        } else {
            // Only save a "removed" revision if there were lists before
            if ($link->lists()->count() > 0) {
                self::createRelationshipRevision(
                    $link,
                    Link::AUDIT_LISTS_NAME,
                    $link->lists->pluck('id')->toArray(),
                    null
                );
            }

            $link->lists()->detach();
        }

        $link->load(['tags', 'lists']);
    }

    /**
     * Create or get the tags from the input and attach them to the link.
     * If links or tags are provided as strings, they are converted into
     * an array of entries, separated by commas.
     *
     * @param Link         $link
     * @param array|string $tags
     */
    protected static function updateTagsForLink(Link $link, array|string $tags): void
    {
        $oldTags = $link->tags->pluck('id');

        $tags = is_array($tags) ? $tags : explode(',', $tags);

        $newTags = self::processTaxonomy(Tag::class, $tags);

        $link->tags()->sync($newTags);

        if ($oldTags->isEmpty() || $oldTags->diff($newTags)->isNotEmpty() || $newTags->diff($oldTags)->isNotEmpty()) {
            self::createRelationshipRevision(
                $link,
                Link::AUDIT_TAGS_NAME,
                $oldTags->toArray(),
                $newTags->toArray()
            );
        }
    }

    /**
     * Create or get the lists from the input and attach them to the link.
     *
     * @param Link         $link
     * @param array|string $lists
     */
    protected static function updateListsForLink(Link $link, array|string $lists): void
    {
        $oldLists = $link->lists->pluck('id');

        $lists = is_array($lists) ? $lists : explode(',', $lists);

        $newLists = self::processTaxonomy(LinkList::class, $lists);

        $link->lists()->sync($newLists);

        if ($oldLists->isEmpty()
            || $oldLists->diff($newLists)->isNotEmpty() || $newLists->diff($oldLists)->isNotEmpty()) {
            self::createRelationshipRevision(
                $link,
                Link::AUDIT_LISTS_NAME,
                $oldLists->toArray(),
                $newLists->toArray()
            );
        }
    }

    /**
     * Tags or lists are passed as comma-delimited strings or integers.
     * If integers are passed we assume that the tags or lists are referenced
     * by their ID. In that case we try to retrieve the tag or list by the
     * provided ID.
     * If tags or lists are passed as strings, we create them and pass the new
     * entity to the taxonomy list.
     *
     * @param string $model
     * @param array  $entries
     * @return Collection
     */
    protected static function processTaxonomy(string $model, array $entries): Collection
    {
        $newEntries = collect();

        $visibilitySetting = match ($model) {
            Tag::class => usersettings('tags_default_visibility') ?? ModelAttribute::VISIBILITY_INTERNAL,
            LinkList::class => usersettings('lists_default_visibility') ?? ModelAttribute::VISIBILITY_INTERNAL,
        };

        foreach ($entries as $entry) {
            if (is_int($entry) && $entry > 0) {
                $newEntry = $model::visibleForUser()->find($entry);
            } else {
                $newEntry = $model::firstOrCreate([
                    'user_id' => auth()->id(),
                    'name' => trim($entry),
                ], [
                    'user_id' => auth()->id(),
                    'name' => trim($entry),
                    'visibility' => $visibilitySetting,
                ]);
            }

            if ($newEntry !== null) {
                $newEntries->push($newEntry->id);
            }
        }

        return $newEntries;
    }

    /**
     * Manually create a new revision for a link if the related tags or lists
     * have changed. Recorded are the IDs instead of names to make sure changes
     * of the corresponding models are taken into account.
     *
     * @param Link       $link
     * @param string     $key
     * @param array|null $oldData
     * @param array|null $newData
     */
    protected static function createRelationshipRevision(
        Link $link,
        string $key,
        ?array $oldData,
        ?array $newData
    ): void {
        $link->auditEvent = Link::AUDIT_RELATION_EVENT;
        $link->isCustomEvent = true;
        $link->auditCustomOld = [
            $key => $oldData,
        ];
        $link->auditCustomNew = [
            $key => $newData,
        ];

        Event::dispatch(new AuditCustom($link));
    }
}
