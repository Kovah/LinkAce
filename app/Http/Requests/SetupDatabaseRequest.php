<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetupDatabaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'connection' => [
                'required',
                'in:sqlite,mysql,pgsql',
            ],
            'db_path' => [
                'required_if:connection,sqlite',
                'not_regex:/[\r\n]/',
            ],
            'db_host' => [
                'required_unless:connection,sqlite',
                'not_regex:/[\r\n]/',
            ],
            'db_port' => [
                'required_unless:connection,sqlite',
                'numeric',
            ],
            'db_name' => [
                'required_unless:connection,sqlite',
                'not_regex:/[\r\n]/',
            ],
            'db_user' => [
                'required_unless:connection,sqlite',
                'not_regex:/[\r\n]/',
            ],
            'db_password' => [
                'nullable',
                'not_regex:/[\r\n]/',
            ],
        ];
    }
}
