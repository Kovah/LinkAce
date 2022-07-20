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
                $entries = Link::onlyTrashed()->byUser()->get();
                break;
            case 'lists':
                $entries = LinkList::onlyTrashed()->byUser()->get();
                break;
            case 'tags':
                $entries = Tag::onlyTrashed()->byUser()->get();
                break;
            case 'notes':
                $entries = Note::onlyTrashed()->byUser()->get();
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
        $entry = match ($model) {
            'link' => Link::withTrashed()->byUser()->findOrFail($id),
            'list' => LinkList::withTrashed()->byUser()->findOrFail($id),
            'tag' => Tag::withTrashed()->byUser()->findOrFail($id),
            'note' => Note::withTrashed()->byUser()->findOrFail($id),
            default => null,
        };

        $entry?->restore();

        return true;
    }
}
