<?php

namespace Tests\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResetPasswordCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommand(): void
    {
        User::factory()->create(['email' => 'test@linkace.org']);

        $this->artisan('reset-password')
            ->expectsQuestion('Please enter the user email address', 'wrong@linkace.org')
            ->expectsOutput('A user with this email address could not be found!')
            ->expectsQuestion('Please enter the user email address', 'test@linkace.org')
            ->expectsQuestion('Please enter a new password for this user', 'test')
            ->expectsOutput('The password must be at least 10 characters.')
            ->expectsQuestion('Please enter a new password for this user', 'longtestpassword')
            ->expectsOutput('Password successfully changed. You can now login.')
            ->assertExitCode(0);

        $loginTest = Auth::attempt([
            'email' => 'test@linkace.org',
            'password' => 'longtestpassword',
        ]);

        $this->assertTrue($loginTest);
    }
}
