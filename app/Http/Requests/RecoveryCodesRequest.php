<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecoveryCodesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'current_password',
            ],
        ];
    }
}
