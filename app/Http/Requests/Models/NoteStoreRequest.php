<?php

namespace App\Http\Requests\Models;

use App\Models\Link;
use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NoteStoreRequest extends FormRequest
{
    public function authorize(Request $request): bool
    {
        return $request->user()->can('view', Link::find($request->input('link_id')));
    }

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
