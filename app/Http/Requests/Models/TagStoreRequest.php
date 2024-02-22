<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagStoreRequest extends FormRequest
{
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
