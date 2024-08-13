<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query' => 'required_without_all:only_lists,only_tags,broken_only,empty_tags,empty_lists',
            'only_lists' => 'required_without_all:query,only_tags,broken_only,empty_tags,empty_lists',
            'only_tags' => 'required_without_all:query,only_lists,broken_only,empty_tags,empty_lists',
            'broken_only' => 'required_without_all:query,only_lists,only_tags,empty_tags,empty_lists',
        ];
    }

    /**
     * Specifies custom error messages for the special validations.
     *
     * @return array
     */
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
