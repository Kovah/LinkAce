<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RegisterUserCommand extends Command
{
    protected $signature = 'registeruser {name? : Username} {email? : User email address}';
    protected $description = 'Register a new user with a given user name and an email address.';

    public function handle(): void
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        if (empty($name)) {
            $name = $this->ask('Please enter the user name:');
        }

        if (empty($email)) {
            $email = $this->ask('Please enter the user email address:');
        }

        // Check if the user exists
        if (User::where('email', $email)->first()) {
            $this->error('An user with the email address "' . $email . '" already exists!');
            return;
        }

        $password = $this->secret('Please enter a password for ' . $name);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('User ' . $name . ' registered.');
    }
}
