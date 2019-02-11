<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Class RegisterUser
 *
 * Provides a php artisan command to register a new user
 * php artisan registeruser UserName mail@user.com mysecretpassword []
 *
 * @package App\Console\Commands
 */
class RegisterUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registeruser {name? : Username} {email? : User email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a new user';

    private $user_roles = [
        'admin',
        'user',
    ];

    /**
     * RegisterUser constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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

        $role = $this->choice('Please choose the user role:', $this->user_roles, 'user');

        $new_user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $new_user->assignRole($role);

        $this->info('User ' . $name . ' registered.');
        return;
    }
}
