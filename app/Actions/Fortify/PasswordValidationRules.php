<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    protected static function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
