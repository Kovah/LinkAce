<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class TagDeleteRequest
 *
 * @package App\Http\Requests
 */
class TagDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Check if the tag ID was provided
        if (!$request->get('tag_id')) {
            return false;
        }

        $tag = Tag::find($request->get('tag_id'));

        // Check if the tag belongs to the user
        if ($tag->user_id !== auth()->user()->id) {
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
            'tag_id' => 'required',
        ];
    }
}
