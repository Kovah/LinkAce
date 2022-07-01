<?php

namespace Tests\Controller\Auth;

use App\Actions\Fortify\CreateUserInvitation;
use App\Enums\Role;
use App\Models\User;
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

    public function testInvitationLink(): void
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

    public function testRegistrationForUser(): void
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
}
