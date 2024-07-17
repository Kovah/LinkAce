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

    public static function bulkUpdate(array $models, array $data): \Illuminate\Support\Collection
    {
        $tags = Tag::whereIn('id', $models)->get();

        return $tags->map(function (Tag $tag) use ($data) {
            if (!auth()->user()->can('update', $tag)) {
                Log::warning('Could not update tag ' . $tag->id . ' during bulk update: Permission denied!');
                return null;
            }

            $tagData = $tag->toArray();
            $tagData['visibility'] = $data['visibility'] ?: $tagData['visibility'];

            return TagRepository::update($tag, $tagData);
        });
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
