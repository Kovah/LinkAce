<?php

namespace App\Http\Requests\Models;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LinkUpdateRequest extends FormRequest
{
    private bool $requireUniqueUrl = false;

    public function authorize(Request $request): bool
    {
        if ($request->input('url') !== null) {
            $this->requireUniqueUrl = $request->route('link')->url !== $request->input('url');
        }

        return true;
    }

    public function rules(): array
    {
        $rules = [
            'url' => [
                'required',
                'string',
            ],
            'title' => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'lists' => 'nullable',
            'tags' => 'nullable',
            'visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
            'check_disabled' => [
                'sometimes',
                'boolean',
            ],
        ];

        if ($this->requireUniqueUrl) {
            $rules['url'] = [
                'required',
                Rule::unique('links')->where(fn($query) => $query->where('user_id', auth()->id())),
            ];
        }

        return $rules;
    }
}
