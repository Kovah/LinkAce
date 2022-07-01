<?php

namespace Tests\Controller\App;

use App\Enums\Role;
use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserInviteNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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

    public function testUserInvitation(): void
    {
        Mail::fake();
        Notification::fake();

        $response = $this->post('system/users/invite', [
            'email' => 'invite-email@linkace.org',
        ]);

        $response->assertRedirect();

        $invitation = UserInvitation::first();
        $this->assertNotNull($invitation);
        $this->assertEquals('invite-email@linkace.org', $invitation->email);

        Notification::assertSentTo($invitation, UserInviteNotification::class);

        // Second invite to the same email fails as the email already exists and the invite is not expired yet
        $response = $this->post('system/users/invite', [
            'email' => 'invite-email@linkace.org',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'The email has already been taken.',
        ]);

        // Travelling 4 days into the future, the first invite expired, so we can invite the email again
        Carbon::setTestNow(now()->addDays(4));

        $response = $this->post('system/users/invite', [
            'email' => 'invite-email@linkace.org',
        ]);
        $response->assertRedirect();

        $this->assertDatabaseCount('user_invitations', 2);
    }

    public function testInviteDeletion(): void
    {
        Mail::fake();
        Notification::fake();

        $this->post('system/users/invite', [
            'email' => 'invite-email@linkace.org',
        ]);

        $this->delete('system/users/invite/1');

        $this->assertDatabaseCount('user_invitations', 0);
    }
}
