<?php

namespace App\Repositories;

use App\Models\LinkList;
use Exception;
use Illuminate\Support\Facades\Log;

class ListRepository
{
    public static function create(array $data): LinkList
    {
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_replace(',', '', $data['name']);

        return LinkList::create($data);
    }

    public static function update(LinkList $list, array $data): LinkList
    {
        $data['name'] = str_replace(',', '', $data['name']);

        $list->update($data);

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
            $list->links()->detach();
            $list->delete();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
