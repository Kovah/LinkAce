<?php

namespace Tests\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListUsersCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommand(): void
    {
        $this->artisan('users:list')
            ->expectsOutput('Searching for all registered users...')
            ->expectsOutput('No users found.')
            ->assertSuccessful();

        User::factory()->create([
            'name' => 'MrTestUser',
            'email' => 'mr-test@linkace.org',
        ]);

        $this->artisan('users:list')
            ->expectsOutput('Searching for all registered users...')
            ->expectsTable(['ID', 'Name', 'Email'], [[1, 'MrTestUser', 'mr-test@linkace.org']])
            ->assertSuccessful();
    }
}
