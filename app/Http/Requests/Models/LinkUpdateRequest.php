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

    /** @var bool */
    private $isApiRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        $this->isApiRequest = $request->isJson();

        $this->requireUniqueUrl = Link::urlHasChanged($request->route('link'), $request->input('url', ''));

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
            'lists' => $this->isApiRequest ? 'array' : 'nullable|string',
            'tags' => $this->isApiRequest ? 'array' : 'nullable|string',
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
