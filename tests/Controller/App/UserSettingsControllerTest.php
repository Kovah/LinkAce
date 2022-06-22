<?php

namespace Tests\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testValidSettingsResponse(): void
    {
        $response = $this->get('settings');

        $response->assertOk()
            ->assertSee('Bookmarklet')
            ->assertSee('API Token')
            ->assertSee('Account Settings')
            ->assertSee('Change Password')
            ->assertSee('User Settings');
    }

    public function testValidUpdateAccountSettingsResponse(): void
    {
        $response = $this->post('settings/account', [
            'name' => 'New Name',
            'email' => 'test@linkace.org',
        ]);

        $response->assertRedirect('/');

        $updatedUser = User::first();

        $this->assertEquals('New Name', $updatedUser->name);
        $this->assertEquals('test@linkace.org', $updatedUser->email);
    }

    public function testValidUpdateApplicationSettingsResponse(): void
    {
        $response = $this->post('settings/app', [
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
            'links_private_default' => '1',
            'notes_private_default' => '1',
            'lists_private_default' => '1',
            'tags_private_default' => '1',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'listitem_count' => '24',
            'link_display_mode' => '1',
            'darkmode_setting' => '0',
        ]);

        $response->assertRedirect('/');

        $this->assertEquals('en_US', usersettings('locale'));
        $this->assertEquals('Europe/Berlin', usersettings('timezone'));
        $this->assertEquals(true, usersettings('links_private_default'));
        $this->assertEquals(true, usersettings('notes_private_default'));
        $this->assertEquals(true, usersettings('lists_private_default'));
        $this->assertEquals(true, usersettings('tags_private_default'));
        $this->assertEquals('Y-m-d', usersettings('date_format'));
        $this->assertEquals('H:i', usersettings('time_format'));
        $this->assertEquals(24, usersettings('listitem_count'));
        $this->assertEquals(1, usersettings('link_display_mode'));
        $this->assertEquals(0, usersettings('darkmode_setting'));
    }

    public function testValidUpdatePasswordResponse(): void
    {
        $response = $this->post('settings/change-password', [
            'current_password' => 'secretpassword',
            'password' => 'newuserpassword',
            'password_confirmation' => 'newuserpassword',
        ]);

        $response->assertRedirect('/');

        $flashMessage = session('flash_notification', collect())->first();
        $this->assertEquals('Password changed successfully!', $flashMessage['message']);

        $this->assertEquals(true, Auth::attempt([
            'email' => $this->user->email,
            'password' => 'newuserpassword',
        ]));
    }
}
