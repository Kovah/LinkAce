<?php

namespace Tests\Components\History;

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

    public function testStringSettingsChange(): void
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

    public function testBooleanSettingsChange(): void
    {
        $settings = app(UserSettings::class);
        $settings->archive_backups_enabled = false;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString('Changed Enable backups for User 1 from <code>Yes</code> to <code>No</code>', $output);
    }

    public function testDarkmodeSettingsChange(): void
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

    public function testDisplayModeSettingsChange(): void
    {
        $settings = app(UserSettings::class);
        $settings->link_display_mode = 2;
        $settings->save();

        $historyEntry = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Link Display Mode for User 1 from <code>cards with less details</code> to <code>list with less details</code>',
            $output
        );
    }

    public function testLocaleSettingsChange(): void
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

    public function testSharingSettingsChange(): void
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

    public function testGuestSettingsChange(): void
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

    public function testUserSettingsChange(): void
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
