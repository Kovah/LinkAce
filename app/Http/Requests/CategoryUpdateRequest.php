<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryUpdateRequest extends FormRequest
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

        $category = Category::find($request->get('category_id'));

        // Check if the category belongs to the user
        if ($category->user_id !== auth()->user()->id) {
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
            'category_id' => 'required',
            'name' => 'required',
            'parent_category' => 'integer',
            'is_private' => 'required|integer',
        ];
    }
}
