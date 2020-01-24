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
     * @param array $data
     * @return Link
     */
    public static function create(array $data): Link
    {
        // Try to get the meta information of the URL if no title / description was provided
        $link_meta = LinkAce::getMetaFromURL($data['url']);

        $data['title'] = $data['title'] ?: $link_meta['title'];
        $data['description'] = $data['description'] ?: $link_meta['description'];
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
     * @param Link   $link
     * @param string $tags
     */
    protected static function updateTagsForLink(Link $link, string $tags): void
    {
        if (empty($tags)) {
            return;
        }

        $parsed_tags = explode(',', $tags);
        $new_tags = [];

        foreach ($parsed_tags as $tag) {
            $new_tag = Tag::firstOrCreate([
                'user_id' => auth()->user()->id,
                'name' => $tag,
            ]);

            $new_tags[] = $new_tag->id;
        }

        $link->tags()->sync($new_tags);
    }

    /**
     * @param Link   $link
     * @param string $lists
     */
    protected static function updateListsForLink(Link $link, string $lists): void
    {
        if (empty($lists)) {
            return;
        }

        $parsed_lists = explode(',', $lists);
        $new_lists = [];

        foreach ($parsed_lists as $list) {
            $new_list = LinkList::firstOrCreate([
                'user_id' => auth()->user()->id,
                'name' => $list,
            ]);

            $new_lists[] = $new_list->id;
        }

        $link->lists()->sync($new_lists);
    }
}
