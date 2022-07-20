<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrashClearRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => [
                'required',
                'in:links,lists,tags,notes',
            ],
        ];
    }
}
