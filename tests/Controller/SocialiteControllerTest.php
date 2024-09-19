<?php

namespace Tests\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Laravel\Socialite\Two\User;
use Tests\TestCase;

class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRedirect(): void
    {
        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->redirect')->once()->andReturn(redirect()->to('https://sso-provider.com/auth'));

        // SSO disabled
        config()->set('auth.sso.enabled', false);
        $this->get('auth/sso/auth0/redirect')->assertStatus(403)->assertSee('Login unauthorized');

        // SSO enabled but wrong provider
        config()->set('auth.sso.enabled', true);
        $this->get('auth/sso/hello/redirect')->assertStatus(403)->assertSee('Login unauthorized');

        // SSO enabled but disabled provider
        $this->get('auth/sso/auth0/redirect')->assertStatus(403)->assertSee('The selected SSO provider is not available.');

        // SSO and corresponding driver enabled
        config()->set('services.auth0.enabled', true);
        $this->get('auth/sso/auth0/redirect')->assertRedirect('https://sso-provider.com/auth');
    }

    public function testRegularSsoLogin(): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'name' => 'SSO User',
            'nickname' => 'SSOUser',
            'given_name' => 'SSO',
            'family_name' => 'User',
        ]);

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->once()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('services.auth0.enabled', true);

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'SSOUser',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
            'sso_token' => 'XF3hkrEeyYkLnTf1fKX',
        ]);
    }

    public function testLoginWithExistingSsoUser(): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'name' => 'SSO User',
            'nickname' => 'SSOUser',
            'given_name' => 'SSO',
            'family_name' => 'User',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'MrPurpleHat',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
        ]);

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->once()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('services.auth0.enabled', true);

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'SSOUser',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
            'sso_token' => 'XF3hkrEeyYkLnTf1fKX',
        ]);
    }

    public function testLoginWithExistingSsoUserWrongProvider(): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'name' => 'SSO User',
            'nickname' => 'SSOUser',
            'given_name' => 'SSO',
            'family_name' => 'User',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'MrPurpleHat',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'oidc123456',
            'sso_provider' => 'oidc',
        ]);

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->once()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('services.auth0.enabled', true);

        $this->get('auth/sso/auth0/callback')->assertStatus(403)->assertSee('Unable to login with Auth0. Please use OIDC to login');
    }

    public function testLoginWithExistingEmail(): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'name' => 'SSO User',
            'nickname' => 'SSOUser',
            'given_name' => 'SSO',
            'family_name' => 'User',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'MrPurpleHat',
            'email' => 'sso-user@linkace.org',
        ]);

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->once()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('services.auth0.enabled', true);

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'SSOUser',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
            'sso_token' => 'XF3hkrEeyYkLnTf1fKX',
        ]);
    }
}
