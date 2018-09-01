<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
{
    /** @var Tag */
    private $tag;

    /** @var bool */
    private $unique_validation = false;

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

        $this->tag = Tag::find($request->get('tag_id'));

        // Check if the tag belongs to the user
        if ($this->tag->user_id !== auth()->user()->id) {
            return false;
        }

        // Enable unique validation if the url was changed
        if ($this->tag->name !== $request->get('name')) {
            $this->unique_validation = true;
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
        if (!$this->unique_validation) {
            return [
                'tag_id' => 'required',
                'name' => 'required',
                'is_private' => 'required|boolean',
            ];
        }

        return [
            'tag_id' => 'required',
            'name' => [
                'required',
                Rule::unique('tags')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }),
            ],
            'is_private' => 'required|boolean',
        ];
    }
}
