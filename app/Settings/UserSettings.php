<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public string $timezone;
    public string $date_format;
    public string $time_format;
    public string $locale;

    public bool $links_private_default;
    public bool $notes_private_default;
    public bool $lists_private_default;
    public bool $tags_private_default;

    public bool $archive_backups_enabled;
    public bool $archive_private_backups_enabled;

    public int $listitem_count;
    public int $darkmode_setting;
    public int $link_display_mode;
    public bool $links_new_tab;
    public bool $markdown_for_text;

    public bool $share_email;
    public bool $share_buffer;
    public bool $share_evernote;
    public bool $share_facebook;
    public bool $share_flipboard;
    public bool $share_hackernews;
    public bool $share_linkedin;
    public bool $share_mastodon;
    public bool $share_pinterest;
    public bool $share_pocket;
    public bool $share_reddit;
    public bool $share_skype;
    public bool $share_sms;
    public bool $share_telegram;
    public bool $share_trello;
    public bool $share_tumblr;
    public bool $share_twitter;
    public bool $share_wechat;
    public bool $share_whatsapp;
    public bool $share_xing;

    public static function group(): string
    {
        return 'user-' . auth()->id();
    }
}
