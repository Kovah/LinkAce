<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class TagRepository
{
    /**
     * @param array $data
     * @return Tag
     */
    public static function create(array $data): Tag
    {
        $data['user_id'] = auth()->user()->id;

        return Tag::create($data);
    }

    /**
     * @param Tag   $tag
     * @param array $data
     * @return Tag
     */
    public static function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag;
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public static function delete(Tag $tag): bool
    {
        try {
            $tag->links()->detach();
            $tag->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
