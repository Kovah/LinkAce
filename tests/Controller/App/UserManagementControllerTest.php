<?php

namespace Tests\Controller\App;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->user->assignRole(Role::ADMIN);
    }

    public function testManagementAccessForUsers(): void
    {
        // No access for regular users
        $this->user->syncRoles(Role::USER);

        $response = $this->get('system/users');
        $response->assertForbidden();

        // Access granted for admins
        $this->user->syncRoles(Role::ADMIN);

        $response = $this->get('system/users');
        $response->assertOk();
    }

    public function testValidSettingsResponse(): void
    {
        $response = $this->get('system/users');

        $response->assertOk()->assertSee('User Management')
            ->assertSee($this->user->name);
    }

    public function testUserBlocking(): void
    {
        $otherUser = User::factory()->create();

        $response = $this->patch('system/users/2/block');
        $response->assertRedirect();

        $this->assertTrue($otherUser->refresh()->isBlocked());

        $this->actingAs($otherUser);

        $response = $this->get('dashboard');
        $response->assertForbidden();

        $this->actingAs($this->user);

        $response = $this->patch('system/users/2/unblock');
        $response->assertRedirect();

        $this->assertFalse($otherUser->refresh()->isBlocked());
    }

    public function testUserDeletion(): void
    {
        $otherUser = User::factory()->create();

        $response = $this->delete('system/users/2/delete');
        $response->assertRedirect();

        $this->assertTrue($otherUser->refresh()->trashed());

        $response = $this->patch('system/users/2/restore');
        $response->assertRedirect();

        $this->assertFalse($otherUser->refresh()->trashed());
    }
}
