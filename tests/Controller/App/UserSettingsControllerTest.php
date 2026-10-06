<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\Note;
use App\Models\User;
use App\Settings\UserSettings;
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

    public function test_valid_settings_response(): void
    {
        $response = $this->get('settings');

        $response->assertOk()
            ->assertSee('Bookmarklet')
            ->assertSee('API Token')
            ->assertSee('Account Settings')
            ->assertSee('Change Password')
            ->assertSee('User Settings');
    }

    public function test_valid_update_account_settings_response(): void
    {
        $response = $this->post('settings/account', [
            'name' => 'NewName',
            'email' => 'test@linkace.org',
        ])->assertSessionHasNoErrors();

        $response->assertRedirect('/');

        $updatedUser = User::notSystem()->first();

        $this->assertEquals('NewName', $updatedUser->name);
        $this->assertEquals('test@linkace.org', $updatedUser->email);
    }

    public function test_valid_update_application_settings_response(): void
    {
        $response = $this->post('settings/app', [
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
            'links_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'notes_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'lists_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'listitem_count' => '24',
            'link_display_mode' => '1',
            'darkmode_setting' => '0',
        ]);

        $response->assertRedirect('/');

        $this->assertEquals('en_US', usersettings('locale'));
        $this->assertEquals('Europe/Berlin', usersettings('timezone'));
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, usersettings('links_default_visibility'));
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, usersettings('notes_default_visibility'));
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, usersettings('lists_default_visibility'));
        $this->assertEquals(ModelAttribute::VISIBILITY_PRIVATE, usersettings('tags_default_visibility'));
        $this->assertEquals('Y-m-d', usersettings('date_format'));
        $this->assertEquals('H:i', usersettings('time_format'));
        $this->assertEquals(24, usersettings('listitem_count'));
        $this->assertEquals(1, usersettings('link_display_mode'));
        $this->assertEquals(0, usersettings('darkmode_setting'));
    }

    public function test_invalid_locale_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'locale' => '../../../../tmp/x',
        ]));

        $response->assertSessionHasErrors('locale');

        $this->assertEquals('en_US', usersettings('locale'));
    }

    public function test_unknown_locale_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'locale' => 'xx_XX',
        ]));

        $response->assertSessionHasErrors('locale');

        $this->assertEquals('en_US', usersettings('locale'));
    }

    public function test_invalid_timezone_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'timezone' => 'Totally/Invalid',
        ]));

        $response->assertSessionHasErrors('timezone');

        $this->assertEquals('UTC', usersettings('timezone'));
    }

    public function test_invalid_date_format_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'date_format' => '\\"\\>\\<\\i\\m\\g \\s\\r\\c\\=\\x\\>',
        ]));

        $response->assertSessionHasErrors('date_format');

        $this->assertEquals('Y-m-d', usersettings('date_format'));
    }

    public function test_invalid_time_format_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'time_format' => '\\<\\s\\c\\r\\i\\p\\t\\>',
        ]));

        $response->assertSessionHasErrors('time_format');

        $this->assertEquals('H:i', usersettings('time_format'));
    }

    public function test_invalid_listitem_count_is_rejected(): void
    {
        $response = $this->post('settings/app', $this->validAppSettings([
            'listitem_count' => '999999',
        ]));

        $response->assertSessionHasErrors('listitem_count');

        $this->assertEquals(24, usersettings('listitem_count'));
    }

    public function test_unknown_settings_keys_are_not_saved(): void
    {
        $this->post('settings/app', $this->validAppSettings([
            'profile_is_public' => '1',
        ]))->assertSessionHasNoErrors();

        $this->assertFalse(usersettings('profile_is_public'));
    }

    public function test_date_format_is_escaped_when_rendering_a_link(): void
    {
        UserSettings::fake([
            'timezone' => 'UTC',
            'date_format' => '\\"\\>\\<\\i\\m\\g \\s\\r\\c\\=\\x\\>',
            'time_format' => 'H:i',
        ]);

        $link = Link::factory()->create(['user_id' => $this->user->id]);

        $this->assertStringNotContainsString('<img src=x>', $link->addedAt());
    }

    public function test_date_format_is_escaped_when_rendering_a_note(): void
    {
        UserSettings::fake([
            'timezone' => 'UTC',
            'date_format' => '\\"\\>\\<\\i\\m\\g \\s\\r\\c\\=\\x\\>',
            'time_format' => 'H:i',
        ]);

        $link = Link::factory()->create(['user_id' => $this->user->id]);
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
            'link_id' => $link->id,
        ]);

        $this->assertStringNotContainsString('<img src=x>', $note->addedAt());
    }

    public function test_valid_update_password_response(): void
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

    /**
     * Returns a complete, valid payload for the application settings form,
     * with the given values overwritten.
     */
    private function validAppSettings(array $overwrites = []): array
    {
        return array_merge([
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
            'date_format' => 'd.m.Y',
            'time_format' => 'h:i a',
            'listitem_count' => '60',
            'links_new_tab' => '1',
            'markdown_for_text' => '1',
            'archive_backups_enabled' => '1',
            'archive_private_backups_enabled' => '1',
            'links_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'notes_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'lists_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'darkmode_setting' => '0',
        ], $overwrites);
    }
}
