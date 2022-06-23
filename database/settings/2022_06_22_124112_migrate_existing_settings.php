<?php

use App\Enums\ModelAttribute;
use Illuminate\Support\Collection;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class MigrateExistingSettings extends SettingsMigration
{
    protected Collection $sysSettings;
    protected Collection $userSettings;

    public function up(): void
    {
        $this->sysSettings = DB::table('old_settings')->whereNull('user_id')->pluck('value', 'key');
        $this->userSettings = DB::table('old_settings')->whereNotNull('user_id')->pluck('value', 'key');

        $this->migrateSystemSettings();
        $this->migrateGuestSettings();
        $this->migrateUserSettings();
    }

    protected function migrateSystemSettings(): void
    {
        $this->migrator->add('system.page_title', $this->sysSettings->get('system_page_title'));
        $this->migrator->add('system.cron_token', $this->sysSettings->get('cron_token'));
        $this->migrator->add(
            'system.guest_access_enabled',
            (bool)$this->sysSettings->get('system_guest_access', false)
        );
        $this->migrator->add('system.setup_completed', (bool)$this->sysSettings->get('system_setup_completed', false));
        $this->migrator->add('system.custom_header_content', $this->sysSettings->get('system_custom_header_content'));
    }

    protected function migrateGuestSettings()
    {
        $this->migrator->add('guest.listitem_count', (int)$this->sysSettings->get('guest_listitem_count', 24));
        $this->migrator->add('guest.link_display_mode', (int)$this->sysSettings->get('guest_link_display_mode', 1));
        $this->migrator->add('guest.links_new_tab', (bool)$this->sysSettings->get('guest_links_new_tab', true));
        $this->migrator->add('guest.darkmode_setting', (int)$this->sysSettings->get('guest_darkmode_setting', 1));

        $this->migrator->add('guest.share_email', (bool)$this->sysSettings->get('guest_share_email', false));
        $this->migrator->add('guest.share_buffer', (bool)$this->sysSettings->get('guest_share_buffer', false));
        $this->migrator->add('guest.share_evernote', (bool)$this->sysSettings->get('guest_share_evernote', false));
        $this->migrator->add('guest.share_facebook', (bool)$this->sysSettings->get('guest_share_facebook', false));
        $this->migrator->add('guest.share_flipboard', (bool)$this->sysSettings->get('guest_share_flipboard', false));
        $this->migrator->add('guest.share_hackernews', (bool)$this->sysSettings->get('guest_share_hackernews', false));
        $this->migrator->add('guest.share_linkedin', (bool)$this->sysSettings->get('guest_share_linkedin', false));
        $this->migrator->add('guest.share_mastodon', (bool)$this->sysSettings->get('guest_share_mastodon', false));
        $this->migrator->add('guest.share_pinterest', (bool)$this->sysSettings->get('guest_share_pinterest', false));
        $this->migrator->add('guest.share_pocket', (bool)$this->sysSettings->get('guest_share_pocket', false));
        $this->migrator->add('guest.share_reddit', (bool)$this->sysSettings->get('guest_share_reddit', false));
        $this->migrator->add('guest.share_skype', (bool)$this->sysSettings->get('guest_share_skype', false));
        $this->migrator->add('guest.share_sms', (bool)$this->sysSettings->get('guest_share_sms', false));
        $this->migrator->add('guest.share_telegram', (bool)$this->sysSettings->get('guest_share_telegram', false));
        $this->migrator->add('guest.share_trello', (bool)$this->sysSettings->get('guest_share_trello', false));
        $this->migrator->add('guest.share_tumblr', (bool)$this->sysSettings->get('guest_share_tumblr', false));
        $this->migrator->add('guest.share_twitter', (bool)$this->sysSettings->get('guest_share_twitter', false));
        $this->migrator->add('guest.share_wechat', (bool)$this->sysSettings->get('guest_share_wechat', false));
        $this->migrator->add('guest.share_whatsapp', (bool)$this->sysSettings->get('guest_share_whatsapp', false));
        $this->migrator->add('guest.share_xing', (bool)$this->sysSettings->get('guest_share_xing', false));
    }

    protected function migrateUserSettings(): void
    {
        $this->migrator->add(
            'user-1.timezone',
            $this->userSettings->get('timezone', 'UTC')
        );
        $this->migrator->add(
            'user-1.date_format',
            $this->userSettings->get('date_format', config('linkace.default.date_format'))
        );
        $this->migrator->add(
            'user-1.time_format',
            $this->userSettings->get('time_format', config('linkace.default.time_format'))
        );
        $this->migrator->add(
            'user-1.locale',
            $this->userSettings->get('locale', config('app.fallback_locale'))
        );

        $this->migrator->add(
            'user-1.links_default_visibility',
            $this->userSettings->get('links_private_default', false)
                ? ModelAttribute::VISIBILITY_PRIVATE : ModelAttribute::VISIBILITY_PUBLIC
        );
        $this->migrator->add(
            'user-1.notes_default_visibility',
            $this->userSettings->get('notes_private_default', false)
                ? ModelAttribute::VISIBILITY_PRIVATE : ModelAttribute::VISIBILITY_PUBLIC
        );
        $this->migrator->add(
            'user-1.lists_default_visibility',
            $this->userSettings->get('lists_private_default', false)
                ? ModelAttribute::VISIBILITY_PRIVATE : ModelAttribute::VISIBILITY_PUBLIC
        );
        $this->migrator->add(
            'user-1.tags_default_visibility',
            $this->userSettings->get('tags_private_default', false)
                ? ModelAttribute::VISIBILITY_PRIVATE : ModelAttribute::VISIBILITY_PUBLIC
        );

        $this->migrator->add(
            'user-1.archive_backups_enabled',
            (bool)$this->userSettings->get('archive_backups_enabled', true)
        );
        $this->migrator->add(
            'user-1.archive_private_backups_enabled',
            (bool)$this->userSettings->get('archive_private_backups_enabled', true)
        );

        $this->migrator->add('user-1.listitem_count', (int)$this->userSettings->get('listitem_count', 24));
        $this->migrator->add('user-1.darkmode_setting', (int)$this->userSettings->get('darkmode_setting', 2));
        $this->migrator->add('user-1.link_display_mode', (int)$this->userSettings->get('link_display_mode', 1));
        $this->migrator->add('user-1.links_new_tab', (bool)$this->userSettings->get('links_new_tab', false));
        $this->migrator->add('user-1.markdown_for_text', (bool)$this->userSettings->get('markdown_for_text', true));

        $this->migrator->add('user-1.share_email', (bool)$this->userSettings->get('share_email', true));
        $this->migrator->add('user-1.share_buffer', (bool)$this->userSettings->get('share_buffer', true));
        $this->migrator->add('user-1.share_evernote', (bool)$this->userSettings->get('share_evernote', true));
        $this->migrator->add('user-1.share_facebook', (bool)$this->userSettings->get('share_facebook', true));
        $this->migrator->add('user-1.share_flipboard', (bool)$this->userSettings->get('share_flipboard', true));
        $this->migrator->add('user-1.share_hackernews', (bool)$this->userSettings->get('share_hackernews', true));
        $this->migrator->add('user-1.share_linkedin', (bool)$this->userSettings->get('share_linkedin', true));
        $this->migrator->add('user-1.share_mastodon', (bool)$this->userSettings->get('share_mastodon', true));
        $this->migrator->add('user-1.share_pinterest', (bool)$this->userSettings->get('share_pinterest', true));
        $this->migrator->add('user-1.share_pocket', (bool)$this->userSettings->get('share_pocket', true));
        $this->migrator->add('user-1.share_reddit', (bool)$this->userSettings->get('share_reddit', true));
        $this->migrator->add('user-1.share_skype', (bool)$this->userSettings->get('share_skype', true));
        $this->migrator->add('user-1.share_sms', (bool)$this->userSettings->get('share_sms', true));
        $this->migrator->add('user-1.share_telegram', (bool)$this->userSettings->get('share_telegram', true));
        $this->migrator->add('user-1.share_trello', (bool)$this->userSettings->get('share_trello', true));
        $this->migrator->add('user-1.share_tumblr', (bool)$this->userSettings->get('share_tumblr', true));
        $this->migrator->add('user-1.share_twitter', (bool)$this->userSettings->get('share_twitter', true));
        $this->migrator->add('user-1.share_wechat', (bool)$this->userSettings->get('share_wechat', true));
        $this->migrator->add('user-1.share_whatsapp', (bool)$this->userSettings->get('share_whatsapp', true));
        $this->migrator->add('user-1.share_xing', (bool)$this->userSettings->get('share_xing', true));
    }
}
