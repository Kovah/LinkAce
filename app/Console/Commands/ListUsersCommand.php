<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsersCommand extends Command
{
    protected $signature = 'users:list';

    protected $description = 'List all active users with user name and email';

    public function handle(): void
    {
        $this->info('Searching for all registered users...');

        $users = User::query()->notSystem()->get(['id', 'name', 'email']);

        if ($users->isEmpty()) {
            $this->info('No users found.');
            return;
        }

        $this->table(['ID', 'Name', 'Email'], $users->toArray());
    }
}
