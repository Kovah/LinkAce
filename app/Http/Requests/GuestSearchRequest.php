<?php

namespace App\Http\Requests;

class GuestSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        return [
            'query' => [
                'required_without_all:only_lists,only_tags',
            ],
            'only_lists' => [
                'required_without_all:query,only_tags',
            ],
            'only_tags' => [
                'required_without_all:query,only_lists',
            ],
            'visibility' => ['prohibited'],
            'broken_only' => ['prohibited'],
            'empty_tags' => ['prohibited'],
            'empty_lists' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'query.required_without_all' => trans('search.validation_query_missing'),
            'only_lists.required_without_all' => trans('search.validation_query_missing'),
            'only_tags.required_without_all' => trans('search.validation_query_missing'),
        ];
    }
}
