<?php

namespace App\Http\Requests\Models;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class TagUpdateRequest
 *
 * @package App\Http\Requests\Models
 */
class TagUpdateRequest extends FormRequest
{
    /** @var bool */
    private $requireUniqueName = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        if ($request->input('name') !== null) {
            $this->requireUniqueName = $request->route('tag')->name !== $request->input('name');
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
        $rules = [
            'name' => 'required',
            'is_private' => 'required|boolean',
        ];

        if ($this->requireUniqueName) {
            $rules['name'] = [
                'required',
                Rule::unique('tags')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}
