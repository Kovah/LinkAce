<?php

namespace App\Actions\Settings;

use App\Models\User;
use App\Settings\UserSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SetDefaultSettingsForUser extends SettingsMigration
{
    public function __construct(protected User $user)
    {
        parent::__construct();
    }

    public function up(): void
    {
        $group = 'user-' . $this->user->id;
        $defaults = UserSettings::defaults();

        $this->migrator->add($group . '.timezone', $defaults['timezone']);
        $this->migrator->add($group . '.date_format', $defaults['date_format']);
        $this->migrator->add($group . '.time_format', $defaults['time_format']);
        $this->migrator->add($group . '.locale', $defaults['locale']);

        $this->migrator->add($group . '.profile_is_public', $defaults['profile_is_public']);

        $this->migrator->add($group . '.links_default_visibility', $defaults['links_default_visibility']);
        $this->migrator->add($group . '.notes_default_visibility', $defaults['notes_default_visibility']);
        $this->migrator->add($group . '.lists_default_visibility', $defaults['lists_default_visibility']);
        $this->migrator->add($group . '.tags_default_visibility', $defaults['tags_default_visibility']);

        $this->migrator->add($group . '.archive_backups_enabled', $defaults['archive_backups_enabled']);
        $this->migrator->add($group . '.archive_private_backups_enabled', $defaults['archive_private_backups_enabled']);

        $this->migrator->add($group . '.listitem_count', $defaults['listitem_count']);
        $this->migrator->add($group . '.darkmode_setting', $defaults['darkmode_setting']);
        $this->migrator->add($group . '.link_display_mode', $defaults['link_display_mode']);
        $this->migrator->add($group . '.links_new_tab', $defaults['links_new_tab']);
        $this->migrator->add($group . '.markdown_for_text', $defaults['markdown_for_text']);

        $this->migrator->add($group . '.share_email', $defaults['share_services']);
        $this->migrator->add($group . '.share_buffer', $defaults['share_services']);
        $this->migrator->add($group . '.share_evernote', $defaults['share_services']);
        $this->migrator->add($group . '.share_facebook', $defaults['share_services']);
        $this->migrator->add($group . '.share_flipboard', $defaults['share_services']);
        $this->migrator->add($group . '.share_hackernews', $defaults['share_services']);
        $this->migrator->add($group . '.share_linkedin', $defaults['share_services']);
        $this->migrator->add($group . '.share_mastodon', $defaults['share_services']);
        $this->migrator->add($group . '.share_pinterest', $defaults['share_services']);
        $this->migrator->add($group . '.share_pocket', $defaults['share_services']);
        $this->migrator->add($group . '.share_reddit', $defaults['share_services']);
        $this->migrator->add($group . '.share_skype', $defaults['share_services']);
        $this->migrator->add($group . '.share_sms', $defaults['share_services']);
        $this->migrator->add($group . '.share_telegram', $defaults['share_services']);
        $this->migrator->add($group . '.share_trello', $defaults['share_services']);
        $this->migrator->add($group . '.share_tumblr', $defaults['share_services']);
        $this->migrator->add($group . '.share_twitter', $defaults['share_services']);
        $this->migrator->add($group . '.share_wechat', $defaults['share_services']);
        $this->migrator->add($group . '.share_whatsapp', $defaults['share_services']);
        $this->migrator->add($group . '.share_xing', $defaults['share_services']);
    }
}
