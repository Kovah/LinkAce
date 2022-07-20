<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'import-file' => [
                'required',
                'file',
                'mimes:html,htm',
            ],
        ];
    }
}
