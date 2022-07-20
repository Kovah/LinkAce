<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                Rule::unique('links')->where(fn($query) => $query->where('user_id', auth()->user()->id)),
            ],
            'title' => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'lists' => [
                'nullable',
            ],
            'tags' => [
                'nullable',
            ],
            'visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
            'check_disabled' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
