<?php

namespace App\Repositories;

use App\Helper\LinkAce;
use App\Helper\LinkIconMapper;
use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Log;

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
     * @return Link
     */
    public static function create(array $data): Link
    {
        $linkMeta = LinkAce::getMetaFromURL($data['url']);

        $data['title'] = $data['title'] ?? $linkMeta['title'];
        $data['description'] = $data['description'] ?? $linkMeta['description'];
        $data['user_id'] = auth()->user()->id;
        $data['icon'] = LinkIconMapper::mapLink($data['url']);

        $link = Link::create($data);

        if (isset($data['tags'])) {
            self::updateTagsForLink($link, $data['tags']);
        }

        if (isset($data['lists'])) {
            self::updateListsForLink($link, $data['lists']);
        }

        SaveLinkToWaybackmachine::dispatch($link);

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

        if (isset($data['tags'])) {
            self::updateTagsForLink($link, $data['tags']);
        }

        if (isset($data['lists'])) {
            self::updateListsForLink($link, $data['lists']);
        }

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

    /**
     * Create or get the tags from the input and attach them to the link.
     *
     * @param Link         $link
     * @param array|string $tags
     */
    protected static function updateTagsForLink(Link $link, $tags): void
    {
        if (is_array($tags)) {
            $newTags = self::processTaxonomyAsArray(Tag::class, $tags);
        } else {
            $newTags = self::processTaxonomyAsString(Tag::class, $tags);
        }

        $link->tags()->sync($newTags);
    }

    /**
     * Create or get the lists from the input and attach them to the link.
     *
     * @param Link         $link
     * @param array|string $lists
     */
    protected static function updateListsForLink(Link $link, $lists): void
    {
        if (is_array($lists)) {
            $newLists = self::processTaxonomyAsArray(LinkList::class, $lists);
        } else {
            $newLists = self::processTaxonomyAsString(LinkList::class, $lists);
        }

        $link->lists()->sync($newLists);
    }

    /**
     * Tags or lists are passed as comma-delimited string in the LinkAce
     * frontend. Parsing the string also creates the corresponding tag or list
     * if it does not exist already.
     *
     * @param string $model
     * @param string $tags
     * @return array
     */
    protected static function processTaxonomyAsString(string $model, string $tags): array
    {
        $parsedTags = explode(',', $tags);
        $newTags = [];

        foreach ($parsedTags as $tag) {
            $newTag = $model::firstOrCreate([
                'user_id' => auth()->user()->id,
                'name' => $tag,
            ]);

            $newTags[] = $newTag->id;
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
     * @return array
     */
    protected static function processTaxonomyAsArray(string $model, array $data): array
    {
        $entries = [];

        foreach ($data as $entry) {
            if ($model::first($entry)) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }
}
