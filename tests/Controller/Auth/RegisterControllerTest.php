<?php

namespace Tests\Controller\Auth;

use App\Actions\Fortify\CreateUserInvitation;
use App\Enums\Role;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        $this->actingAs($admin);
    }

    public function test_invitation_link(): void
    {
        // Create user invitation and logout admin
        $invitation = CreateUserInvitation::run('invitation@linkace.org');
        Auth::logout();

        $url = $invitation->inviteUrl();

        $response = $this->get($url);
        $response->assertOk()->assertSee('Register')->assertSee('invitation@linkace.org');

        // Mess with the invitation token > invitation invalid
        $url = str_replace('token=', 'token=abcd', $url);

        $response = $this->get($url);
        $response->assertStatus(401)->assertSee('The invitation is expired or the link is incorrect.');

        // Jump into the future > invitation expired
        Carbon::setTestNow(now()->addDays(4));
        $url = $invitation->inviteUrl();

        $response = $this->get($url);
        $response->assertStatus(401)->assertSee('The invitation is expired or the link is incorrect.');
        Carbon::setTestNow();

        // Invitation was already used
        $invitation->created_user_id = 5;
        $invitation->saveQuietly();

        $response = $this->get($url);
        $response->assertStatus(401)->assertSee('The invitation is expired or was already used.');

        // Delete the invitation before it can be used
        $invitation->delete();

        $response = $this->get($url);
        $response->assertStatus(401)->assertSee('The invitation link is invalid or the invitation was deleted.');
    }

    public function test_registration_for_user(): void
    {
        // Create user invitation and logout admin
        $invitation = CreateUserInvitation::run('invitation@linkace.org');
        Auth::logout();

        $response = $this->post('auth/register', [
            'token' => $invitation->token,
            'email' => 'invitation@linkace.org',
            'name' => 'testuser',
            'password' => 'sometestpassword',
            'password_confirmation' => 'sometestpassword',
        ]);

        $response->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'invitation@linkace.org',
            'name' => 'testuser',
        ]);
    }

    public function test_registration_with_a_different_email_is_rejected(): void
    {
        $invitation = CreateUserInvitation::run('invitation@linkace.org');
        Auth::logout();

        $response = $this->post('auth/register', [
            'token' => $invitation->token,
            'email' => 'attacker@linkace.org',
            'name' => 'attacker',
            'password' => 'sometestpassword',
            'password_confirmation' => 'sometestpassword',
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('users', ['email' => 'attacker@linkace.org']);
        $this->assertGuest();
    }

    public function test_invitation_cannot_be_used_twice(): void
    {
        $invitation = CreateUserInvitation::run('invitation@linkace.org');
        Auth::logout();

        $this->post('auth/register', [
            'token' => $invitation->token,
            'email' => 'invitation@linkace.org',
            'name' => 'testuser',
            'password' => 'sometestpassword',
            'password_confirmation' => 'sometestpassword',
        ])->assertRedirect('dashboard');

        Auth::logout();

        // Rejected either by the invitation check or by the unique email rule
        $this->post('auth/register', [
            'token' => $invitation->token,
            'email' => 'invitation@linkace.org',
            'name' => 'seconduser',
            'password' => 'sometestpassword',
            'password_confirmation' => 'sometestpassword',
        ]);

        $this->assertDatabaseMissing('users', ['name' => 'seconduser']);
        $this->assertGuest();
    }

    public function test_invitation_is_consumed_only_once(): void
    {
        $invitation = CreateUserInvitation::run('invitation@linkace.org');

        // A second request which read the invitation before it was consumed
        $staleInvitation = UserInvitation::find($invitation->id);

        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->assertTrue($invitation->consumeFor($firstUser));
        $this->assertFalse($staleInvitation->consumeFor($secondUser));

        $this->assertDatabaseHas('user_invitations', [
            'id' => $invitation->id,
            'created_user_id' => $firstUser->id,
        ]);

        // Consuming an invitation must still be recorded in the audit log
        $this->assertDatabaseHas('audits', [
            'auditable_type' => UserInvitation::class,
            'auditable_id' => $invitation->id,
            'event' => 'updated',
        ]);
    }

    public function test_registration_rolls_back_when_the_invitation_was_consumed(): void
    {
        $invitation = CreateUserInvitation::run('invitation@linkace.org');
        $otherUser = User::factory()->create();

        // Simulate a concurrent request winning the race
        UserInvitation::whereKey($invitation->id)->update(['created_user_id' => $otherUser->id]);

        Auth::logout();

        $response = $this->post('auth/register', [
            'token' => $invitation->token,
            'email' => 'invitation@linkace.org',
            'name' => 'raceloser',
            'password' => 'sometestpassword',
            'password_confirmation' => 'sometestpassword',
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('users', ['name' => 'raceloser']);
    }
}
