<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ListStoreRequest
 *
 * @package App\Http\Requests\Models
 */
class ListStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_private' => 'sometimes|boolean',
        ];
    }
}
