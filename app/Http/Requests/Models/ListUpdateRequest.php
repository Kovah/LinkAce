<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class ListUpdateRequest
 *
 * @package App\Http\Requests\Models
 */
class ListUpdateRequest extends FormRequest
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
            $this->requireUniqueName = $request->route('list')->name !== $request->input('name');
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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_private' => 'sometimes|boolean',
        ];

        if ($this->requireUniqueName) {
            $rules['name'] = [
                'required',
                Rule::unique('lists')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}
