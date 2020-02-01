<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    public function testValidSettingsResponse(): void
    {
        $response = $this->get('settings');

        $response->assertStatus(200)
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

        $response->assertStatus(302);

        $updatedUser = User::first();

        $this->assertEquals('New Name', $updatedUser->name);
        $this->assertEquals('test@linkace.org', $updatedUser->email);
    }

    public function testValidUpdateApplicationSettingsResponse(): void
    {
        $response = $this->post('settings/app', [
            'timezone' => 'Europe/Berlin',
            'private_default' => '1',
            'notes_private_default' => '1',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'listitem_count' => '25',
            'link_display_mode' => '1',
            'darkmode_setting' => '0',
        ]);

        $response->assertStatus(302);

        /*
         * @TODO The following checks are not working due to an eager-loaded relation of the user being empty
         * A user exists, auth()->user() correctly returns it. The issue is that the user has a relation
         * to the Setting model which is eager-loaded as $user->rawSettings->... and then made available
         * via the settings() method. Eager loading should prevent multiple database calls for each single
         * setting being checked. This works correctly in the app.
         * However, for whatever reason, this eager loaded model is simply empty when run via the PPHunit
         * tests. Despite the fact, that there are settings connected to the user in the database.
         * Maybe some kind of database caching is happening here? I don't know...
         *
         * auth()->user() returns the current user with the ID 1
         * Setting::first() returns a valid setting entry connected to the user via user_id = 1
         * auth()->user()->rawSettings is empty
         * usersettings('...') therefore returns null for everything
         */

        $this->assertEquals('Europe/Berlin', usersettings('timezone'));
        $this->assertEquals('1', usersettings('private_default'));
        $this->assertEquals('1', usersettings('notes_private_default'));
        $this->assertEquals('Y-m-d', usersettings('date_format'));
        $this->assertEquals('H:i', usersettings('time_format'));
        $this->assertEquals('25', usersettings('listitem_count'));
        $this->assertEquals('1', usersettings('link_display_mode'));
        $this->assertEquals('0', usersettings('darkmode_setting'));
    }

    public function testValidUpdatePasswordResponse(): void
    {
        $response = $this->post('settings/change-password', [
            'old_password' => 'secretpassword',
            'new_password' => 'newuserpassword',
            'new_password_confirmation' => 'newuserpassword',
        ]);

        $response->assertStatus(302);

        $this->assertEquals('Password changed successfully!', session()->get('alert.message'));

        $this->assertEquals(true, Auth::attempt([
            'email' => $this->user->email,
            'password' => 'newuserpassword',
        ]));
    }
}
