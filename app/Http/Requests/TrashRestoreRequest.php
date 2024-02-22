<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrashRestoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => [
                'required',
                'in:link,list,tag,note',
            ],
            'id' => [
                'required',
                'numeric',
            ],
        ];
    }
}
