<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class LinkUpdateRequest
 *
 * @package App\Http\Requests
 */
class LinkUpdateRequest extends FormRequest
{
    /** @var bool */
    private $requireUniqueUrl = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        $this->requireUniqueUrl = Link::hasUrlChanged($request->route('link'), $request->input('url', ''));

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
            'link_id' => 'required',
            'url' => 'required',
            'title' => 'present',
            'description' => 'present',
            'lists' => 'present',
            'tags' => 'present',
            'is_private' => 'required|boolean',
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
