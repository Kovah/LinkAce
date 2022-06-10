<?php

namespace Tests\Components\History;

use App\Models\Setting;
use App\Models\User;
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
        $setting = Setting::create(['key' => 'timezone', 'value' => 'Europe/Berlin']);
        $setting->update(['value' => 'UTC']);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Timezone from <code>Europe/Berlin</code> to <code>UTC</code>',
            $output
        );
    }

    public function testBooleanSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'archive_backups_enabled', 'value' => true]);
        $setting->update(['value' => false]);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString('Changed Enable backups from <code>Yes</code> to <code>No</code>', $output);
    }

    public function testDarkmodeSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'darkmode_setting', 'value' => 1]);
        $setting->update(['value' => 2]);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Darkmode from <code>Permanent</code> to <code>Automatically</code>',
            $output
        );
    }

    public function testDisplayModeSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'link_display_mode', 'value' => 1]);
        $setting->update(['value' => 2]);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Link Display Mode from <code>cards with less details</code> to <code>list with less details</code>',
            $output
        );
    }

    public function testLocaleSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'locale', 'value' => 'en_US']);
        $setting->update(['value' => 'de_DE']);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Language from <code>English</code> to <code>Deutsch</code>',
            $output
        );
    }

    public function testSharingSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'share_email', 'value' => true]);
        $setting->update(['value' => false]);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Link Sharing: Email from <code>Yes</code> to <code>No</code>',
            $output
        );
    }

    public function testGuestSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'guest_listitem_count', 'value' => 24]);
        $setting->update(['value' => 60]);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Guest Settings: Number of Items in Lists from <code>24</code> to <code>60</code>',
            $output
        );
    }

    public function testUserSettingsChange(): void
    {
        $setting = Setting::create(['key' => 'locale', 'value' => 'en_US', 'user_id' => 1]);
        $setting->update(['value' => 'de_DE']);

        $historyEntry = Audit::where('auditable_type', Setting::class)->with('auditable')->latest()->first();

        $output = (new SettingsEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Language for User 1 from <code>English</code> to <code>Deutsch</code>',
            $output
        );
    }
}
