<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\LinkList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ListDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Check if the category ID was provided
        if (!$request->get('category_id')) {
            return false;
        }

        $list = LinkList::find($request->get('category_id'));

        // Check if the category belongs to the user
        if ($list->user_id !== auth()->user()->id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'list_id' => 'required',
        ];
    }
}
