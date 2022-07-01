<?php

namespace App\Http\Requests\Auth;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    use PasswordValidationRules;

    public function rules(Request $request): array
    {
        return [
            'token' => ['required'],
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ];
    }
}
