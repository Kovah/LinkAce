<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function authorize(Request $request): bool
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
    public function rules(): array
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
