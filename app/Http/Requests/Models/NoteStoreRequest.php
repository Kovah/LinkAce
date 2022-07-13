<?php

namespace App\Http\Requests\Models;

use App\Models\Link;
use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NoteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('view', Link::find($request->input('link_id')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'link_id' => [
                'required',
            ],
            'note' => [
                'required',
            ],
            'visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
        ];
    }
}
