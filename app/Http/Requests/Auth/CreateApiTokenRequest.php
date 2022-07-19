<?php

namespace App\Http\Requests\Auth;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateApiTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token_name' => [
                'required',
                'alpha_dash',
                'min:3',
                'max:100',
                Rule::unique('personal_access_tokens', 'name')->where(function (Builder $query) {
                    return $query->where('tokenable_id', request()->user()->id);
                }),
            ],
        ];
    }
}
