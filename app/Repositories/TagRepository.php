<?php

namespace App\Repositories;

use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Log;

class TagRepository
{
    public static function create(array $data): Tag
    {
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_replace(',', '', $data['name']);

        return Tag::create($data);
    }

    public static function update(Tag $tag, array $data): Tag
    {
        $data['name'] = str_replace(',', '', $data['name']);

        $tag->update($data);

        return $tag;
    }

    public static function delete(Tag $tag): bool
    {
        try {
            $tag->links()->detach();
            $tag->delete();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
