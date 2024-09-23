<?php

namespace App\Http\Requests\Models\Api;

use App\Rules\ModelVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreTagsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['array'],
            'models.*.name' => [
                'required',
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->user()->id)),
            ],
            'models.*.visibility' => [
                'sometimes',
                new ModelVisibility(),
            ],
        ];
    }
}
