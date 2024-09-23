<?php

namespace App\Http\Requests\Models\Api;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;

class BulkStoreListsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['array'],
            'models.*.name' => [
                'required',
                'string',
            ],
            'models.*.description' => [
                'nullable',
                'string',
            ],
            'models.*.visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
        ];
    }
}
