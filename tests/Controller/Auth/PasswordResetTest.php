<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_view(): void
    {
        $confirmView = $this->get('forgot-password');
        $confirmView->assertSee('forgot-password');
    }

    public function test_forgot_password_request(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@linkace.org',
        ]);

        $response = $this->post('forgot-password', [
            'email' => 'reset@linkace.org',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_url_is_not_poisoned_by_forwarded_host(): void
    {
        Notification::fake();
        config(['app.url' => 'https://linkace.example.com']);

        $user = User::factory()->create([
            'email' => 'reset@linkace.org',
        ]);

        $this->post('forgot-password', ['email' => 'reset@linkace.org'], [
            'X-Forwarded-Host' => 'attacker.com',
        ]);

        $this->assertResetUrlPointsToTheApplication($user);
    }

    public function test_reset_url_is_not_poisoned_by_host_header(): void
    {
        Notification::fake();
        config(['app.url' => 'https://linkace.example.com']);

        $user = User::factory()->create([
            'email' => 'reset@linkace.org',
        ]);

        $this->post('forgot-password', ['email' => 'reset@linkace.org'], [
            'Host' => 'attacker.com',
        ]);

        $this->assertResetUrlPointsToTheApplication($user);
    }

    private function assertResetUrlPointsToTheApplication(User $user): void
    {
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;

            $this->assertStringStartsWith('https://linkace.example.com/reset-password/', $url);
            $this->assertStringNotContainsString('attacker.com', $url);

            return true;
        });
    }

    public function test_password_reset_view(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@linkace.org',
        ]);

        $token = app('auth.password.broker')->createToken($user);

        $confirmView = $this->get('reset-password/' . $token);
        $confirmView->assertSee('reset-password');
    }

    public function test_password_reset_request(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@linkace.org',
        ]);

        $token = app('auth.password.broker')->createToken($user);

        $confirmView = $this->post('reset-password/', [
            'token' => $token,
            'email' => 'reset@linkace.org',
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
        ]);

        $confirmView->assertRedirect('login');

        $loginAttempt = Auth::attempt([
            'email' => 'reset@linkace.org',
            'password' => 'newPassword',
        ]);

        self::assertTrue($loginAttempt);
    }
}
