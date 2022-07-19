<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected static function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
