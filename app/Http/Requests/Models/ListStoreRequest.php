<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;

class ListStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_private' => 'sometimes|boolean',
        ];
    }
}
