<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class RegisterUserCommand extends Command
{
    protected $signature = 'registeruser {name? : Username} {email? : User email address} {password? : Password for the user} {--admin}';
    protected $description = 'Register a new user with a given user name and an email address.';

    private ?string $userName;
    private ?string $userEmail;
    private ?string $userPassword;
    private bool $validationFailed = false;

    public function handle(): void
    {
        if ($this->option('admin') === false && User::query()->count() <= 1) {
            $this->warn('It seems you are creating the first user. This user should be an admin!');
            $this->warn('Please consider running the command with --admin again.');
        }

        $this->userName = $this->argument('name');
        $this->userEmail = $this->argument('email');
        $this->userPassword = $this->argument('password');

        do {
            $this->askForUserDetails();

            try {
                $newUser = (new CreateNewUser)->create([
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

        if ($this->option('admin')) {
            $newUser->assignRole(Role::ADMIN);
        }

        $this->info('User ' . $this->userName . ' registered.');
    }

    protected function askForUserDetails(): void
    {
        if (empty($this->userName) || $this->validationFailed) {
            $this->userName = $this->ask(
                'Please enter the user name containing only alpha-numeric characters, dashes or underscores',
                $this->userName
            );
        }

        if (empty($this->userEmail) || $this->validationFailed) {
            $this->userEmail = $this->ask('Please enter the user email address', $this->userEmail);
        }

        if (empty($this->userPassword) || $this->validationFailed) {
            $this->userPassword = $this->ask('Please enter a password for ' . $this->userName, $this->userPassword);
        }
    }
}
