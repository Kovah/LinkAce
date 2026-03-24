<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_login_response(): void
    {
        $response = $this->get('login');

        $response->assertOk()
            ->assertSee('Login');
    }

    public function test_valid_login_redirect(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('login');

        $response->assertRedirect('dashboard');
    }

    public function test_valid_login_submit(): void
    {
        $user = User::factory()->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ]);

        $response->assertRedirect('dashboard');
    }

    public function test_invalid_login_submit(): void
    {
        $user = User::factory()->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_confirm_password_view(): void
    {
        $user = User::factory()->create();

        $confirmView = $this->actingAs($user)->get('user/confirm-password');
        $confirmView->assertSee('Confirmation required');
    }

    public function test_login_response_is_forbidden_when_proxy_auth_is_enabled(): void
    {
        config()->set('auth.proxy.enabled', true);

        $this->get('login')
            ->assertForbidden()
            ->assertSee('Authentication is handled by a reverse proxy');
    }

    public function test_login_submit_is_forbidden_when_sso_is_enabled(): void
    {
        config()->set('auth.sso.enabled', true);

        $user = User::factory()->create();

        $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ])->assertForbidden()->assertSee('This login method is disabled');
    }
}
