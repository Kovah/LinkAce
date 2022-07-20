<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSettingsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => [
                'required',
            ],
            'timezone' => [
                'required',
            ],
        ];
    }
}
