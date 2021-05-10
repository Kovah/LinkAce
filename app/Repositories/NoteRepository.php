<?php

namespace App\Repositories;

use App\Models\Note;
use Exception;
use Illuminate\Support\Facades\Log;

class NoteRepository
{
    /**
     * @param array $data
     * @return Note
     */
    public static function create(array $data): Note
    {
        $data['user_id'] = auth()->user()->id;

        return Note::create($data);
    }

    /**
     * @param Note  $note
     * @param array $data
     * @return Note
     */
    public static function update(Note $note, array $data): Note
    {
        $data['is_private'] = $data['is_private'] ?? false;

        $note->update($data);

        return $note;
    }

    /**
     * @param Note $note
     * @return bool
     */
    public static function delete(Note $note): bool
    {
        try {
            $note->delete();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
