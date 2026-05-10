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
                'required_without_all:only_lists,only_tags,exclude_lists,exclude_tags,broken_only,empty_tags,empty_lists',
            ],
            'only_lists' => [
                'required_without_all:query,only_tags,exclude_lists,exclude_tags,broken_only,empty_tags,empty_lists',
            ],
            'only_tags' => [
                'required_without_all:query,only_lists,exclude_lists,exclude_tags,broken_only,empty_tags,empty_lists',
            ],
            'exclude_lists' => [
                'required_without_all:query,only_lists,only_tags,exclude_tags,broken_only,empty_tags,empty_lists',
            ],
            'exclude_tags' => [
                'required_without_all:query,only_lists,only_tags,exclude_lists,broken_only,empty_tags,empty_lists',
            ],
            'broken_only' => [
                'required_without_all:query,only_lists,only_tags,exclude_lists,exclude_tags,empty_tags,empty_lists',
            ],
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

    public function messages(): array
    {
        return [
            'query.required_without_all' => trans('search.validation_query_missing'),
            'only_lists.required_without_all' => trans('search.validation_query_missing'),
            'only_tags.required_without_all' => trans('search.validation_query_missing'),
            'exclude_lists.required_without_all' => trans('search.validation_query_missing'),
            'exclude_tags.required_without_all' => trans('search.validation_query_missing'),
            'broken_only.required_without_all' => trans('search.validation_query_missing'),
        ];
    }
}
