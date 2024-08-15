<?php

namespace App\Http\Requests;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => [
                'required_without_all:only_lists,only_tags,broken_only,empty_tags,empty_lists',
            ],
            'only_lists' => [
                'required_without_all:query,only_tags,broken_only,empty_tags,empty_lists',
            ],
            'only_tags' => [
                'required_without_all:query,only_lists,broken_only,empty_tags,empty_lists',
            ],
            'broken_only' => [
                'required_without_all:query,only_lists,only_tags,empty_tags,empty_lists',
            ],
            'visibility' => [
                'sometimes',
                'nullable',
                'integer',
                new ModelVisibility(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'query.required_without_all' => trans('search.validation_query_missing'),
            'only_lists.required_without_all' => trans('search.validation_query_missing'),
            'only_tags.required_without_all' => trans('search.validation_query_missing'),
            'broken_only.required_without_all' => trans('search.validation_query_missing'),
        ];
    }
}
