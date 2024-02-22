<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NoteUpdateRequest extends FormRequest
{
    public function authorize(Request $request): bool
    {
        return $request->user()->can('view', $request->route('note')->link);
    }

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
