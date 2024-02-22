<?php

namespace Tests\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandWithInput(): void
    {
        User::factory()->create(); // Create admin dummy user

        $this->artisan('registeruser', [
            'name' => 'Test',
            'email' => 'test@linkace.org',
        ])
            ->expectsQuestion('Please enter a password for Test', 'testpassword')
            ->expectsOutput('User Test registered.')
            ->assertExitCode(0);

        $databaseUser = User::latest('id')->first();

        $this->assertEquals('Test', $databaseUser->name);
        $this->assertEquals('test@linkace.org', $databaseUser->email);
    }

    public function testCommandWithoutInput(): void
    {
        User::factory()->create(); // Create admin dummy user

        $this->artisan('registeruser')
            ->expectsQuestion('Please enter the user name containing only alpha-numeric characters, dashes or underscores', 'Test')
            ->expectsQuestion('Please enter the user email address', 'test@linkace.org')
            ->expectsQuestion('Please enter a password for Test', 'testpassword')
            ->expectsOutput('User Test registered.')
            ->assertExitCode(0);

        $databaseUser = User::latest('id')->first();

        $this->assertEquals('Test', $databaseUser->name);
        $this->assertEquals('test@linkace.org', $databaseUser->email);
    }

    public function testCommandWithDuplicateUser(): void
    {
        User::factory()->create(['name' => 'Test', 'email' => 'test@linkace.org']);

        $this->artisan('registeruser', [
            'name' => 'Test',
            'email' => 'test@linkace.org',
        ])
            ->expectsQuestion('Please enter a password for Test', 'testpassword')
            ->expectsOutput('The name has already been taken.')
            ->expectsOutput('The email has already been taken.')
            ->expectsQuestion('Please enter the user name containing only alpha-numeric characters, dashes or underscores', 'Test2')
            ->expectsQuestion('Please enter the user email address', 'test2@linkace.org')
            ->expectsQuestion('Please enter a password for Test2', 'testpassword')
            ->expectsOutput('User Test2 registered.')
            ->assertExitCode(0)
        ;
    }
}
