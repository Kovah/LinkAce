<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;

class BulkEditLinksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['required', 'string'],
            'tags' => ['nullable', 'string'],
            'tags_mode' => ['required', 'in:append,replace'],
            'lists' => ['nullable', 'string'],
            'lists_mode' => ['required', 'in:append,replace'],
            'visibility' => ['nullable', new ModelVisibility],
        ];
    }
}
