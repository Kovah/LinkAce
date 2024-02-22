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
