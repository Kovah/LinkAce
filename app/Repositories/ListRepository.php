<?php

namespace App\Repositories;

use App\Models\Link;
use App\Models\LinkList;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ListRepository
{
    public static function create(array $data): LinkList
    {
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_replace(',', '', $data['name']);

        $list = LinkList::withoutSyncingToSearch(fn () => LinkList::create($data));

        self::syncListToSearch($list);

        return $list;
    }

    public static function update(LinkList $list, array $data): LinkList
    {
        $data['name'] = str_replace(',', '', $data['name']);

        LinkList::withoutSyncingToSearch(fn () => $list->update($data));
        self::syncListToSearch($list);

        return $list;
    }

    public static function bulkUpdate(array $models, array $data): \Illuminate\Support\Collection
    {
        $lists = LinkList::whereIn('id', $models)->get();

        return $lists->map(function (LinkList $list) use ($data) {
            if (!auth()->user()->can('update', $list)) {
                Log::warning('Could not update list ' . $list->id . ' during bulk update: Permission denied!');
                return null;
            }

            $listData = $list->toArray();
            $listData['visibility'] = $data['visibility'] ?: $listData['visibility'];

            return ListRepository::update($list, $listData);
        });
    }

    public static function delete(LinkList $list): bool
    {
        try {
            $links = self::searchIndexingEnabled()
                ? $list->links()->get()
                : collect();

            $list->links()->detach();
            LinkList::withoutSyncingToSearch(fn () => $list->delete());
            self::removeListFromSearch($list);
            self::syncLinksToSearch($links);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }

    private static function syncListToSearch(LinkList $list): void
    {
        if (!self::searchIndexingEnabled()) {
            return;
        }

        $list->searchable();
    }

    private static function removeListFromSearch(LinkList $list): void
    {
        if (!self::searchIndexingEnabled()) {
            return;
        }

        $list->unsearchable();
    }

    private static function syncLinksToSearch(Collection $links): void
    {
        if (!self::searchIndexingEnabled()) {
            return;
        }

        $links->each(function (Link $link): void {
            $link->load(['tags:id', 'lists:id']);
            $link->searchable();
        });
    }

    private static function searchIndexingEnabled(): bool
    {
        return in_array(
            config('linkace.search.driver'),
            config('linkace.search.external_drivers', []),
            true
        );
    }
}
