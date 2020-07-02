<?php

namespace App\Repositories;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;

class TrashRepository
{
    /**
     * Deletes all trashed entries for the currently authenticated user, based
     * on the given model type.
     *
     * @param string $model
     * @return bool
     */
    public static function delete(string $model): bool
    {
        $entries = collect();

        switch ($model) {
            case 'links':
                $entries = Link::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'lists':
                $entries = LinkList::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'tags':
                $entries = Tag::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'notes':
                $entries = Note::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
        }

        foreach ($entries as $entry) {
            $entry->forceDelete();
        }

        return true;
    }

    /**
     * Restores a specific model based on the given type and model ID.
     *
     * @param string $model
     * @param int    $id
     * @return bool
     */
    public static function restore(string $model, int $id): bool
    {
        switch ($model) {
            case 'link':
                $entry = Link::withTrashed()->findOrFail($id);
                break;
            case 'list':
                $entry = LinkList::withTrashed()->findOrFail($id);
                break;
            case 'tag':
                $entry = Tag::withTrashed()->findOrFail($id);
                break;
            case 'note':
                $entry = Note::withTrashed()->findOrFail($id);
                break;
            default:
                $entry = null;
        }

        $entry->restore();

        return true;
    }
}
