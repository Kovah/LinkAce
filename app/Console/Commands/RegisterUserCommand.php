<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class RegisterUserCommand extends Command
{
    protected $signature = 'registeruser {name? : Username} {email? : User email address}';
    protected $description = 'Register a new user with a given user name and an email address.';

    private ?string $userName;
    private ?string $userEmail;
    private ?string $userPassword;
    private bool $validationFailed = false;

    public function handle(): void
    {
        $this->userName = $this->argument('name');
        $this->userEmail = $this->argument('email');

        do {
            $this->askForUserDetails();

            try {
                (new CreateNewUser)->create([
                    'name' => $this->userName,
                    'email' => $this->userEmail,
                    'password' => $this->userPassword,
                    'password_confirmation' => $this->userPassword,
                ]);

                $this->validationFailed = false;
            } catch (ValidationException $e) {
                $this->validationFailed = true;
                foreach ($e->errors() as $error) {
                    $this->error(implode(' ', $error));
                }
            }
        } while ($this->validationFailed);

        $this->info('User ' . $this->userName . ' registered.');
    }

    protected function askForUserDetails(): void
    {
        if (empty($this->userName) || $this->validationFailed) {
            $this->userName = $this->ask('Please enter the user name containing only alpha-numeric characters, dashes or underscores', $this->userName);
        }

        if (empty($this->userEmail) || $this->validationFailed) {
            $this->userEmail = $this->ask('Please enter the user email address', $this->userEmail);
        }

        $this->userPassword = $this->secret('Please enter a password for ' . $this->userName);
    }
}
