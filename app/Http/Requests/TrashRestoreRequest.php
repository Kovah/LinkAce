<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrashRestoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'model' => 'required|in:link,list,tag,note',
            'id' => 'required|numeric',
        ];
    }
}
