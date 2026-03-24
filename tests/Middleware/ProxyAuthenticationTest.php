<?php

namespace Tests\Middleware;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProxyAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.proxy.enabled', true);
        config()->set('auth.proxy.id_headers', ['X-Auth-Request-Preferred-Username']);
        config()->set('auth.proxy.email_headers', ['X-Auth-Request-Email']);
        config()->set('auth.proxy.name_headers', ['X-Auth-Request-User']);
    }

    public function test_existing_user_is_logged_in_from_proxy_headers(): void
    {
        $user = User::factory()->create([
            'email' => 'proxy-user@linkace.test',
            'name' => 'OldName',
        ]);

        $response = $this->get('login', [
            'X-Auth-Request-Email' => 'proxy-user@linkace.test',
            'X-Auth-Request-User' => 'ProxyUser',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user->fresh());
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'ProxyUser',
        ]);
    }

    public function test_unknown_user_is_rejected_when_auto_create_is_disabled(): void
    {
        config()->set('auth.proxy.auto_create_users', false);

        $this->get('dashboard', [
            'X-Auth-Request-Email' => 'missing-user@linkace.test',
            'X-Auth-Request-User' => 'MissingUser',
        ])->assertForbidden();
    }

    public function test_unknown_user_can_be_auto_created(): void
    {
        config()->set('auth.proxy.auto_create_users', true);

        $response = $this->get('login', [
            'X-Auth-Request-Email' => 'new-user@linkace.test',
            'X-Auth-Request-User' => 'NewUser',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'new-user@linkace.test',
            'name' => 'NewUser',
        ]);
        $this->assertTrue(User::where('email', 'new-user@linkace.test')->first()->hasRole(Role::USER));
    }

    public function test_unknown_user_can_be_auto_created_without_email_when_id_header_exists(): void
    {
        config()->set('auth.proxy.auto_create_users', true);

        $response = $this->get('login', [
            'X-Auth-Request-Preferred-Username' => 'proxy-subject-123',
            'X-Auth-Request-User' => 'ProxyUser',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'ProxyUser',
            'sso_id' => 'proxy-subject-123',
            'sso_provider' => 'proxy',
        ]);
    }

    public function test_blocked_user_is_still_rejected(): void
    {
        User::factory()->create([
            'email' => 'blocked-user@linkace.test',
            'blocked_at' => now(),
        ]);

        $this->get('dashboard', [
            'X-Auth-Request-Email' => 'blocked-user@linkace.test',
            'X-Auth-Request-User' => 'BlockedUser',
        ])->assertForbidden();
    }

    public function test_missing_proxy_identity_returns_forbidden_for_protected_route(): void
    {
        $this->get('dashboard')
            ->assertForbidden()
            ->assertSee('Authentication is handled by a reverse proxy');
    }

    public function test_front_page_returns_forbidden_when_guest_access_is_disabled(): void
    {
        $this->get('/')
            ->assertForbidden()
            ->assertSee('Authentication is handled by a reverse proxy');
    }
}
