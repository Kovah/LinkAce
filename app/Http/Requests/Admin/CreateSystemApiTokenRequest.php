<?php

namespace App\Http\Requests\Admin;

use App\Rules\ApiTokenAbilityRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSystemApiTokenRequest extends FormRequest
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
                    return $query->whereNull('tokenable_id');
                }),
            ],
            'abilities' => [
                'required',
                new ApiTokenAbilityRule(),
            ],
            'private_access' => [
                'sometimes',
                'accepted',
            ],
        ];
    }
}
