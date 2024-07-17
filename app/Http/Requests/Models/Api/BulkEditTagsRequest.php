<?php

namespace App\Http\Requests\Models\Api;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;

class BulkEditTagsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['required', 'array'],
            'visibility' => ['nullable', new ModelVisibility],
        ];
    }
}
