<?php

namespace Tests\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use App\Models\User as LinkAceUser;
use Laravel\Socialite\Two\User;
use Tests\TestCase;

class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect(): void
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

    public function test_regular_sso_login(): void
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

    public function test_sso_login_with_disabled_registration(): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'name' => 'SSO User',
            'nickname' => null,
            'given_name' => 'SSO',
            'family_name' => 'User',
        ]);

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->twice()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('auth.sso.registration_enabled', false);
        config()->set('services.auth0.enabled', true);

        $this->get('auth/sso/auth0/callback')->assertForbidden();

        // Try again after creating a user in the database
        \App\Models\User::factory()->create([
            'name' => 'MrPurpleHat',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
        ]);

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'SSOUser',
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
            'sso_token' => 'XF3hkrEeyYkLnTf1fKX',
        ]);
    }

    public function test_login_with_existing_sso_user(): void
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

    public function test_login_with_existing_sso_user_wrong_provider(): void
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

    public function test_login_with_existing_email(): void
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

    public function test_existing_sso_identity_cannot_be_rebound_by_the_same_provider(): void
    {
        LinkAceUser::factory()->create([
            'name' => 'Victim',
            'email' => 'victim@linkace.org',
            'sso_id' => 'victim-sub-123',
            'sso_provider' => 'auth0',
        ]);

        // The attacker holds a different identity on the same provider, but
        // presents the victim's email address.
        $this->mockSsoUser([
            'id' => 'attacker-sub-999',
            'email' => 'victim@linkace.org',
            'nickname' => 'Attacker',
        ]);

        $this->get('auth/sso/auth0/callback')->assertStatus(403);

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'victim@linkace.org',
            'sso_id' => 'victim-sub-123',
        ]);
        $this->assertDatabaseMissing('users', ['sso_id' => 'attacker-sub-999']);
    }

    public function test_sso_login_is_rejected_when_the_verified_email_claim_is_missing(): void
    {
        config()->set('auth.sso.require_verified_email', true);

        LinkAceUser::factory()->create([
            'name' => 'Victim',
            'email' => 'victim@linkace.org',
        ]);

        $this->mockSsoUser([
            'id' => 'attacker-sub-999',
            'email' => 'victim@linkace.org',
            'nickname' => 'Attacker',
        ]);

        $this->get('auth/sso/auth0/callback')->assertStatus(403);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['sso_id' => 'attacker-sub-999']);
    }

    public function test_sso_login_is_rejected_when_the_verified_email_claim_is_false(): void
    {
        config()->set('auth.sso.require_verified_email', true);

        LinkAceUser::factory()->create(['email' => 'victim@linkace.org']);

        $this->mockSsoUser(
            ['id' => 'attacker-sub-999', 'email' => 'victim@linkace.org', 'nickname' => 'Attacker'],
            ['email_verified' => false],
        );

        $this->get('auth/sso/auth0/callback')->assertStatus(403);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['sso_id' => 'attacker-sub-999']);
    }

    public function test_sso_login_succeeds_with_a_verified_email_claim(): void
    {
        config()->set('auth.sso.require_verified_email', true);

        LinkAceUser::factory()->create(['email' => 'sso-user@linkace.org']);

        $this->mockSsoUser(
            ['id' => 'sso-user-sub-123', 'email' => 'sso-user@linkace.org', 'nickname' => 'SSOUser'],
            ['email_verified' => true],
        );

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
            'sso_provider' => 'auth0',
        ]);
    }

    public function test_sso_login_accepts_a_verified_email_claim_sent_as_a_string(): void
    {
        config()->set('auth.sso.require_verified_email', true);

        LinkAceUser::factory()->create(['email' => 'sso-user@linkace.org']);

        $this->mockSsoUser(
            ['id' => 'sso-user-sub-123', 'email' => 'sso-user@linkace.org', 'nickname' => 'SSOUser'],
            ['email_verified' => 'true'],
        );

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
        ]);
    }

    public function test_unverified_email_is_accepted_while_the_check_is_disabled(): void
    {
        // Off by default, so that providers which cannot supply the claim at all
        // (Azure, GitHub, GitLab) keep working.
        $this->assertFalse(config('auth.sso.require_verified_email'));

        LinkAceUser::factory()->create(['email' => 'sso-user@linkace.org']);

        $this->mockSsoUser([
            'id' => 'sso-user-sub-123',
            'email' => 'sso-user@linkace.org',
            'nickname' => 'SSOUser',
        ]);

        $this->get('auth/sso/auth0/callback')->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'sso-user@linkace.org',
            'sso_id' => 'sso-user-sub-123',
        ]);
    }

    /**
     * Register a Socialite user for a single callback request.
     *
     * @param array $attributes Attributes mapped by the provider driver
     * @param array $raw        Additional claims as returned by the provider
     * @return void
     */
    protected function mockSsoUser(array $attributes, array $raw = []): void
    {
        $ssoUser = new User();
        $ssoUser->setToken('XF3hkrEeyYkLnTf1fKX');
        $ssoUser->map($attributes);
        $ssoUser->setRaw(array_merge($attributes, $raw));

        $this->app->register(SocialiteServiceProvider::class);
        Socialite::shouldReceive('driver->user')->once()->andReturn($ssoUser);

        config()->set('auth.sso.enabled', true);
        config()->set('services.auth0.enabled', true);
    }
}
