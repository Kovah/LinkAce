<?php

namespace App\Actions\Fortify;

use App\Models\UserInvitation;
use Illuminate\Support\Str;

class CreateUserInvitation
{
    public static function run(string $email)
    {
        return UserInvitation::create([
            'token' => Str::random(32),
            'email' => $email,
            'inviter_id' => auth()->id(),
            'valid_until' => now()->addDays(3),
        ]);
    }
}
