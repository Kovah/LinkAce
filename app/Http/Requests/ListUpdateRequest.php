<?php

namespace App\Http\Requests;

use App\Models\LinkList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class ListUpdateRequest
 *
 * @package App\Http\Requests
 */
class ListUpdateRequest extends FormRequest
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
        $this->requireUniqueUrl = LinkList::nameHasChanged($request->route('list'), $request->input('name', ''));

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
            'list_id' => 'required',
            'name' => 'required',
            'is_private' => 'required|integer',
        ];

        if ($this->requireUniqueUrl) {
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
