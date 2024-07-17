<?php

namespace App\Http\Requests\Models\Api;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;

class BulkEditLinksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['required', 'array'],
            'tags' => ['nullable', 'array'],
            'tags_mode' => ['required_with:tags', 'in:append,replace'],
            'lists' => ['nullable', 'array'],
            'lists_mode' => ['required_with:lists', 'in:append,replace'],
            'visibility' => ['nullable', new ModelVisibility],
        ];
    }
}
