<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LinkUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Check if the link ID was provided
        if (!$request->get('link_id')) {
            return false;
        }

        $link = Link::find($request->get('link_id'));

        // Check if the link belongs to the user
        if ($link->user_id !== auth()->user()->id) {
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
            'link_id' => 'required',
            'url' => 'required',
            'title' => 'required',
            'is_private' => 'required|boolean',
        ];
    }
}
