<?php

namespace App\Repositories;

use App\Models\Note;
use Exception;
use Illuminate\Support\Facades\Log;

class NoteRepository
{
    public static function create(array $data): Note
    {
        $data['user_id'] = auth()->user()->id;

        return Note::create($data);
    }

    public static function update(Note $note, array $data): Note
    {
        $note->update($data);

        return $note;
    }

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
