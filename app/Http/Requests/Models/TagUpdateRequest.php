<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
{
    private bool $requireUniqueName = false;

    public function authorize(Request $request): bool
    {
        if ($request->input('name') !== null) {
            $this->requireUniqueName = $request->route('tag')->name !== $request->input('name');
        }

        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
        ];

        if ($this->requireUniqueName) {
            $rules['name'] = [
                'required',
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->id())),
            ];
        }

        return $rules;
    }
}
