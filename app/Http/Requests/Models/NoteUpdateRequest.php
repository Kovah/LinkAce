<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class NoteUpdateRequest
 *
 * @package App\Http\Requests\Models
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
            'note' => 'required',
            'is_private' => 'sometimes|boolean',
        ];
    }
}
