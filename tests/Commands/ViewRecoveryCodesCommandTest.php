<?php

namespace Tests\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewRecoveryCodesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandWith2FaDisabled(): void
    {
        User::factory()->create(['email' => 'test@linkace.org']);

        $this->artisan('2fa:view-recovery-codes')
            ->expectsQuestion('Please enter the user email address', 'wrong@linkace.org')
            ->expectsOutput('A user with this email address could not be found!')
            ->expectsQuestion('Please enter the user email address', 'test@linkace.org')
            ->expectsOutput('Two Factor Authentication is not enabled for this user.')
            ->assertExitCode(0);
    }

    public function testCommand(): void
    {
        $user = User::factory()->create(['email' => 'test@linkace.org']);

        $user->two_factor_recovery_codes = encrypt(json_encode(['test-recovery-code']));
        $user->save();

        $this->artisan('2fa:view-recovery-codes')
            ->expectsQuestion('Please enter the user email address', 'wrong@linkace.org')
            ->expectsOutput('A user with this email address could not be found!')
            ->expectsQuestion('Please enter the user email address', 'test@linkace.org')
            ->expectsOutput('Recovery Codes for user ' . $user->name . ':')
            ->expectsOutput('test-recovery-code')
            ->assertExitCode(0);
    }
}
