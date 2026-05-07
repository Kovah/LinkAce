<?php

namespace App\Http\Requests;

class GuestSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string'],
            'only_lists' => ['nullable', 'string'],
            'only_tags' => ['nullable', 'string'],
            'visibility' => ['prohibited'],
            'broken_only' => ['prohibited'],
            'empty_tags' => ['prohibited'],
            'empty_lists' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
