<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagStoreRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->user()->id)),
            ],
            'visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
        ];
    }
}
