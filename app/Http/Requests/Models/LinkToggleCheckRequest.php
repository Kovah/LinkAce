<?php

namespace App\Http\Requests\Models;

use App\Models\LinkList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class LinkToggleCheckRequest
 *
 * @package App\Http\Requests\Models
 */
class LinkToggleCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
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
            'toggle' => 'required|boolean',
        ];
    }
}
