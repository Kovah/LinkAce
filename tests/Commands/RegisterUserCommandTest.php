<?php

namespace Tests\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCommandWithInput(): void
    {
        $this->artisan('registeruser', [
            'name' => 'Test',
            'email' => 'test@linkace.org',
        ])
            ->expectsQuestion('Please enter a password for Test', 'testpassword')
            ->expectsOutput('User Test registered.')
            ->assertExitCode(0);

        $databaseUser = User::first();

        $this->assertEquals('Test', $databaseUser->name);
        $this->assertEquals('test@linkace.org', $databaseUser->email);
    }

    public function testCommandWithoutInput(): void
    {
        $this->artisan('registeruser')
            ->expectsQuestion('Please enter the user name:', 'Test')
            ->expectsQuestion('Please enter the user email address:', 'test@linkace.org')
            ->expectsQuestion('Please enter a password for Test', 'testpassword')
            ->expectsOutput('User Test registered.')
            ->assertExitCode(0);

        $databaseUser = User::first();

        $this->assertEquals('Test', $databaseUser->name);
        $this->assertEquals('test@linkace.org', $databaseUser->email);
    }

    public function testCommandWithDuplicateUser(): void
    {
        User::factory()->create(['email' => 'test@linkace.org']);

        $this->artisan('registeruser', [
            'name' => 'Test',
            'email' => 'test@linkace.org',
        ])
            ->expectsOutput('An user with the email address "test@linkace.org" already exists!')
            ->assertExitCode(0);
    }
}
