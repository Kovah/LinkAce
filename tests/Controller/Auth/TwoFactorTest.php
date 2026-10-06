<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_login_with2_fa(): void
    {
        $secretKey = (new Google2FA())->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secretKey),
        ]);

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ]);

        $response->assertRedirect('two-factor-challenge');

        $otpView = $this->get('two-factor-challenge');
        $otpView->assertSee('Two Factor Authentication');

        $otp = (new Google2FA())->getCurrentOtp($secretKey);

        $otpResponse = $this->post('two-factor-challenge', [
            'code' => $otp,
        ]);

        $otpResponse->assertRedirect('dashboard');
    }

    public function test_invalid_login_with2_fa(): void
    {
        $secretKey = (new Google2FA())->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secretKey),
        ]);

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ]);

        $response->assertRedirect('two-factor-challenge');

        $otpResponse = $this->post('two-factor-challenge', [
            'code' => '123456789',
        ]);

        $otpResponse->assertRedirect('two-factor-challenge');
    }

    public function test_two_factor_challenge_is_rate_limited(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt((new Google2FA())->generateSecretKey()),
        ]);

        $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ])->assertRedirect('two-factor-challenge');

        $statuses = [];
        for ($i = 0; $i < 6; $i++) {
            $statuses[] = $this->post('two-factor-challenge', ['code' => '000000'])->status();
        }

        $this->assertEquals(429, end($statuses));
    }

    public function test_valid_code_is_accepted_within_the_rate_limit(): void
    {
        $secretKey = (new Google2FA())->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secretKey),
        ]);

        $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ])->assertRedirect('two-factor-challenge');

        $this->post('two-factor-challenge', ['code' => '000000'])
            ->assertRedirect('two-factor-challenge');

        $this->post('two-factor-challenge', ['code' => (new Google2FA())->getCurrentOtp($secretKey)])
            ->assertRedirect('dashboard');
    }

    public function test_recovery_code_login_is_rate_limited(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => encrypt((new Google2FA())->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(['aaaa1-bbbb1'])),
        ]);

        $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ])->assertRedirect('two-factor-challenge');

        $statuses = [];
        for ($i = 0; $i < 6; $i++) {
            $statuses[] = $this->post('two-factor-challenge', ['recovery_code' => 'wrong-code'])->status();
        }

        $this->assertEquals(429, end($statuses));
    }
}
