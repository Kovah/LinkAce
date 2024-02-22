<?php

namespace App\Http\Requests\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RegisterRequest extends FormRequest
{
    use PasswordValidationRules;

    public function rules(): array
    {
        return array_merge(['token' => ['required']], CreateNewUser::rules());
    }
}
