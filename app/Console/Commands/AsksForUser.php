<?php

namespace App\Console\Commands;

use App\Models\User;

trait AsksForUser
{
    /** @var User */
    protected $user;

    protected function askForUser(): void
    {
        do {
            $email = $this->ask('Please enter the user email address');

            $this->user = User::where('email', $email)->first();

            if (empty($this->user)) {
                $this->warn('A user with this email address could not be found!');
            }
        } while (empty($this->user));
    }
}
