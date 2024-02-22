<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetupDatabaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'db_host' => [
                'required',
            ],
            'db_port' => [
                'required',
                'numeric',
            ],
            'db_name' => [
                'required',
            ],
            'db_user' => [
                'required',
            ],
            'db_password' => [
                'nullable',
            ],
        ];
    }
}
