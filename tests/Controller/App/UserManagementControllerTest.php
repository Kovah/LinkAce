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
}
