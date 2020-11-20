<?php

namespace App\Http\Requests\Models;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class LinkUpdateRequest
 *
 * @package App\Http\Requests\Models
 */
class LinkUpdateRequest extends FormRequest
{
    /** @var bool */
    private $requireUniqueUrl;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        if ($request->input('url') !== null) {
            $this->requireUniqueUrl = $request->route('link')->url !== $request->input('url');
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
            'url' => 'required|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'lists' => 'nullable',
            'tags' => 'nullable',
            'is_private' => 'sometimes|boolean',
            'check_disabled' => 'sometimes|boolean',
        ];

        if ($this->requireUniqueUrl) {
            $rules['url'] = [
                'required',
                Rule::unique('links')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}
