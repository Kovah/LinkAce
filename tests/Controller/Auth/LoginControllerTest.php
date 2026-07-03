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

    public function test_sso_auto_redirect(): void
    {
        config(['auth.sso.enabled' => true]);
        config(['auth.sso.regular_login_disabled' => true]);
        config(['auth.sso.auto_redirect' => true]);
        config(['auth.sso.providers' => ['authentik']]);
        config(['services.authentik.enabled' => true]);

        $response = $this->get('login');

        $response->assertRedirect(route('auth.sso.redirect', ['provider' => 'authentik']));
    }
}
