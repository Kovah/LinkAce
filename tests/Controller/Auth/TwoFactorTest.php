<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLoginWith2FA(): void
    {
        $secretKey = (new Google2FA)->generateSecretKey();

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

        $otp = (new Google2FA)->getCurrentOtp($secretKey);

        $otpResponse = $this->post('two-factor-challenge', [
            'code' => $otp,
        ]);

        $otpResponse->assertRedirect('dashboard');
    }

    public function testInvalidLoginWith2FA(): void
    {
        $secretKey = (new Google2FA)->generateSecretKey();

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

        $otpResponse->assertRedirect('login');
    }
}
