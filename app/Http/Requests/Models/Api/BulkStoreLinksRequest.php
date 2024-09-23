<?php

namespace App\Http\Requests\Models\Api;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreLinksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'models' => ['array', 'required'],
            'models.*.user_id' => ['nullable', 'exists:users'],
            'models.*.url' => ['required'],
            'models.*.title' => ['nullable'],
            'models.*.description' => ['nullable'],
            'models.*.icon' => ['nullable'],
            'models.*.thumbnail' => ['nullable'],
            'models.*.status' => ['nullable', 'integer'],
            'models.*.check_disabled' => ['nullable', 'boolean'],
            'models.*.visibility' => ['required', 'integer'],
        ];
    }
}
