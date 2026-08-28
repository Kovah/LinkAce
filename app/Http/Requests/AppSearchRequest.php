<?php

namespace App\Http\Requests;

use App\Rules\ModelVisibility;

class AppSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string'],
            'only_lists' => ['nullable', 'string'],
            'only_tags' => ['nullable', 'string'],
            'exclude_lists' => ['nullable', 'string'],
            'exclude_tags' => ['nullable', 'string'],
            'broken_only' => ['sometimes'],
            'empty_tags' => ['sometimes'],
            'empty_lists' => ['sometimes'],
            'tag_mode' => [
                'sometimes',
                'in:any,all',
            ],
            'list_mode' => [
                'sometimes',
                'in:any,all',
            ],
            'visibility' => [
                'sometimes',
                'nullable',
                'integer',
                new ModelVisibility(),
            ],
        ];
    }
}
