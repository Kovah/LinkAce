<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class TagStoreRequest
 *
 * @package App\Http\Requests\Models
 */
class TagStoreRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('tags')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }),
            ],
            'is_private' => 'required|boolean',
        ];
    }
}
