<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class ResetPasswordCommand extends Command
{
    protected $signature = 'reset-password';

    /** @var User */
    protected $user;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('This tool allows you to reset the password for any user.');

        $this->askForUserEmail();
        $this->resetUserPassword();
    }

    protected function askForUserEmail()
    {
        do {
            $email = $this->ask('Please enter the user email address');

            $this->user = User::where('email', $email)->first();

            if (empty($this->user)) {
                $this->warn('A user with this email address could not be found!');
            }

        } while (empty($this->user));
    }

    protected function resetUserPassword()
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
