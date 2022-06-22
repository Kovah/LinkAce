<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Settings\SetDefaultSettingsForUser;
use App\Models\User;
use Illuminate\Console\Command;

class RegisterUserCommand extends Command
{
    protected $signature = 'registeruser {name? : Username} {email? : User email address}';
    protected $description = 'Register a new user with a given user name and an email address.';

    public function handle(): void
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        if (empty($name)) {
            $name = $this->ask('Please enter the user name');
        }

        if (empty($email)) {
            $email = $this->ask('Please enter the user email address');
        }

        // Check if the user exists
        if (User::where('email', $email)->first()) {
            $this->error('An user with the email address "' . $email . '" already exists!');
            return;
        }

        $password = $this->secret('Please enter a password for ' . $name);

        $user = (new CreateNewUser)->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        (new SetDefaultSettingsForUser($user))->up();

        $this->info('User ' . $name . ' registered.');
    }
}
