<?php

namespace App\Repositories;

use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Venturecraft\Revisionable\Revisionable;

/**
 * Class LinkRepository
 *
 * @package App\Repositories
 */
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
        $linkMeta = HtmlMeta::getFromUrl($data['url'], $flashAlerts);

        $data['title'] = $data['title'] ?? $linkMeta['title'];
        $data['description'] = $data['description'] ?? $linkMeta['description'];
        $data['user_id'] = auth()->user()->id;
        $data['icon'] = LinkIconMapper::mapLink($data['url']);

        // If the meta helper was not successfull, disable future checks and set the status to broken
        if ($linkMeta['success'] === false) {
            $data['check_disabled'] = true;
            $data['status'] = Link::STATUS_BROKEN;
        }

        /** @var Link $link */
        $link = Link::create($data);

        self::processLinkTaxonmies($link, $data);

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
        $data['icon'] = LinkIconMapper::mapLink($data['url'] ?? $link->url);

        $link->update($data);

        self::processLinkTaxonmies($link, $data);

        return $link;
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
            $link->delete();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }

    protected static function processLinkTaxonmies(Link $link, array $data)
    {
        if (isset($data['tags'])) {
            self::updateTagsForLink($link, $data['tags']);
        } else {
            // Only save a "removed" revision if there were tags before
            if ($link->tags()->count() > 0) {
                self::createRelationshipRevision(
                    $link,
                    Link::REV_TAGS_NAME,
                    $link->tags->pluck('id')->join(','),
                    null
                );
            }

            $link->tags()->detach();
        }

        if (isset($data['lists'])) {
            self::updateListsForLink($link, $data['lists']);
        } else {
            // Only save a "removed" revision if there were lists before
            if ($link->lists()->count() > 0) {
                self::createRelationshipRevision(
                    $link,
                    Link::REV_LISTS_NAME,
                    $link->lists->pluck('id')->join(','),
                    null
                );
            }

            $link->lists()->detach();
        }
    }

    /**
     * Create or get the tags from the input and attach them to the link.
     *
     * @param Link         $link
     * @param array|string $tags
     */
    protected static function updateTagsForLink(Link $link, $tags): void
    {
        $oldTags = $link->tags->pluck('id');

        if (is_array($tags)) {
            $newTags = self::processTaxonomyAsArray(Tag::class, $tags);
        } else {
            $newTags = self::processTaxonomyAsString(Tag::class, $tags);
        }

        $link->tags()->sync($newTags);

        if ($oldTags->isEmpty() || $oldTags->diff($newTags)->isNotEmpty()) {
            self::createRelationshipRevision(
                $link,
                Link::REV_TAGS_NAME,
                $oldTags->join(','),
                $newTags->join(',')
            );
        }
    }

    /**
     * Create or get the lists from the input and attach them to the link.
     *
     * @param Link         $link
     * @param array|string $lists
     */
    protected static function updateListsForLink(Link $link, $lists): void
    {
        $oldLists = $link->lists->pluck('id');

        if (is_array($lists)) {
            $newLists = self::processTaxonomyAsArray(LinkList::class, $lists);
        } else {
            $newLists = self::processTaxonomyAsString(LinkList::class, $lists);
        }

        $link->lists()->sync($newLists);

        if ($oldLists->isEmpty() || $oldLists->diff($newLists)->isNotEmpty()) {
            self::createRelationshipRevision(
                $link,
                Link::REV_LISTS_NAME,
                $oldLists->join(','),
                $newLists->join(',')
            );
        }
    }

    /**
     * Tags or lists are passed as comma-delimited string in the LinkAce
     * frontend. Parsing the string also creates the corresponding tag or list
     * if it does not exist already.
     *
     * @param string $model
     * @param string $tags
     * @return Collection
     */
    protected static function processTaxonomyAsString(string $model, string $tags): Collection
    {
        $parsedTags = explode(',', $tags);
        $newTags = collect();

        foreach ($parsedTags as $tag) {
            $newTag = $model::firstOrCreate([
                'user_id' => auth()->user()->id,
                'name' => $tag,
            ]);

            $newTags->push($newTag->id);
        }

        return $newTags;
    }

    /**
     * Tags or lists are passed as arrays containing the model IDs in API
     * calls. The passed IDs are first checked for existence before allowing
     * them to be synced with the link.
     *
     * @param string $model
     * @param array  $data
     * @return Collection
     */
    protected static function processTaxonomyAsArray(string $model, array $data): Collection
    {
        $entries = collect();

        foreach ($data as $entry) {
            if ($model::find($entry)) {
                $entries->push($entry);
            }
        }

        return $entries;
    }

    /**
     * Manually create a new revision for a link if the related tags or lists
     * have changed. Recorded are the IDs instead of names to make sure changes
     * of the corresponding models are taken into account.
     *
     * @param Link   $link
     * @param string $key
     * @param mixed  $oldData
     * @param mixed  $newData
     */
    protected static function createRelationshipRevision(Link $link, string $key, $oldData, $newData)
    {
        $revision = [
            'revisionable_type' => $link->getMorphClass(),
            'revisionable_id' => $link->getKey(),
            'key' => $key,
            'old_value' => $oldData,
            'new_value' => $newData,
            'user_id' => $link->getSystemUserId(),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ];

        $revisionable = Revisionable::newModel();

        DB::table($revisionable->getTable())->insert($revision);
    }
}
