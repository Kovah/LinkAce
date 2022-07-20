<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    public function update($user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => self::passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            if (!Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', trans('settings.old_password_invalid'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
