<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NoteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('view', $request->route('note')->link);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
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
