<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class ResetPasswordCommand extends Command
{
    use AsksForUser;

    protected $signature = 'reset-password';

    protected $description = 'Reset the password for a given user without the need of configuring email sending.';

    public function handle(): void
    {
        $this->line('This tool allows you to reset the password for any user.');

        $this->askForUser();
        $this->resetUserPassword();
    }

    protected function resetUserPassword(): void
    {
        do {
            $newPassword = $this->secret('Please enter a new password for this user');

            $validator = Validator::make(['password' => $newPassword], [
                'password' => 'required|string|min:10',
            ]);

            if ($validator->invalid()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->warn($error);
                }
            }
        } while ($validator->invalid());

        $this->user->password = bcrypt($newPassword);
        $this->user->save();

        $this->info('Password successfully changed. You can now login.');
    }
}
