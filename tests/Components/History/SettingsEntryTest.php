<?php

namespace Tests\Components\History;

use App\Enums\ModelAttribute;
use App\Models\User;
use App\Settings\GuestSettings;
use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use App\Settings\UserSettings;
use App\View\Components\History\SettingsEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OwenIt\Auditing\Models\Audit;
use Tests\TestCase;

class SettingsEntryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['name' => 'TestUser']);
        $this->actingAs($user);
    }

    public function test_string_settings_change(): void
    {
        $settings = app(SystemSettings::class);
        $settings->page_title = 'A new Page Title';
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Page Title from <code></code> to <code>A new Page Title</code>',
            $output
        );
    }

    public function test_boolean_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->archive_backups_enabled = false;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString('Changed Enable backups for User 1 from <code>Yes</code> to <code>No</code>', $output);
    }

    public function test_darkmode_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->darkmode_setting = 0;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Darkmode for User 1 from <code>Automatically</code> to <code>Disabled</code>',
            $output
        );
    }

    public function test_locale_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->locale = 'de_DE';
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Language for User 1 from <code>English</code> to <code>Deutsch</code>',
            $output
        );
    }

    public function test_poisoned_locale_settings_change_still_renders(): void
    {
        // An unknown locale may have been stored before it was validated.
        // Audit rows cannot be deleted, so the log must still render.
        $settings = app(UserSettings::class);
        $settings->locale = 'zz_ZZ';
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Language for User 1 from <code>English</code> to <code>zz_ZZ</code>',
            $output
        );
    }

    public function test_poisoned_darkmode_settings_change_still_renders(): void
    {
        $settings = app(UserSettings::class);
        $settings->darkmode_setting = 99;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Darkmode for User 1 from <code>Automatically</code> to <code>99</code>',
            $output
        );
    }

    public function test_visibility_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->links_default_visibility = ModelAttribute::VISIBILITY_PRIVATE;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Default Links visibility for User 1 from <code>Public</code> to <code>Private</code>',
            $output
        );
    }

    public function test_sharing_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->share_email = false;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Link Sharing: Email from <code>Yes</code> to <code>No</code>',
            $output
        );
    }

    public function test_guest_settings_change(): void
    {
        $settings = app(GuestSettings::class);
        $settings->listitem_count = 60;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Guest Settings: Number of Items in Lists from <code>24</code> to <code>60</code>',
            $output
        );
    }

    public function test_user_settings_change(): void
    {
        $settings = app(UserSettings::class);
        $settings->locale = 'de_DE';
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Language for User 1 from <code>English</code> to <code>Deutsch</code>',
            $output
        );
    }
}
