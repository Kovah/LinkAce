<?php

namespace App\Http\Requests;

use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class NoteUpdateRequest
 *
 * @package App\Http\Requests
 */
class NoteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Check if the note ID was provided
        if (!$request->get('note_id')) {
            return false;
        }

        $note = Note::find($request->get('note_id'));

        // Check if the note belongs to the user
        if ($note->user_id !== auth()->user()->id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note_id' => 'required',
            'note' => 'required',
            'is_private' => 'boolean',
        ];
    }
}
