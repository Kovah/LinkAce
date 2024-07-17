<?php

namespace App\Http\Requests\Models\Api;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:links,lists,tags'],
            'models' => ['required', 'array'],
        ];
    }
}
