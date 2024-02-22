<?php

namespace App\Http\Requests\Models;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ToggleLinkCheckRequest extends FormRequest
{
    public function authorize(Request $request): bool
    {
        return $request->user()->can('update', $request->link);
    }

    public function rules(): array
    {
        return [
            'toggle' => [
                'required',
                'boolean',
            ],
        ];
    }
}
