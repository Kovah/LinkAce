<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NoteStoreRequest
 *
 * @package App\Http\Requests\Models
 */
class NoteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'link_id' => 'required',
            'note' => 'required',
            'is_private' => 'boolean',
        ];
    }
}
