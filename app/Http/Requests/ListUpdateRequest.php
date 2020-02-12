<?php

namespace App\Http\Requests;

use App\Models\LinkList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class ListUpdateRequest
 *
 * @package App\Http\Requests
 */
class ListUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
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
            'name' => 'required',
            'is_private' => 'required|integer',
        ];
    }
}
