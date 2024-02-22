<?php

namespace App\Settings;

use App\Enums\ModelAttribute;
use App\Models\Link;
use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public string $timezone;
    public string $date_format;
    public string $time_format;
    public string $locale;

    public bool $profile_is_public;

    public int $links_default_visibility;
    public int $notes_default_visibility;
    public int $lists_default_visibility;
    public int $tags_default_visibility;

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

    private static int $userId = 0;

    public static function group(): string
    {
        return 'user-' . self::getUserId();
    }

    public static function setUserId(int $userId): void
    {
        self::$userId = $userId;
    }

    // By default, settings are scoped to the currently authenticated user
    protected static function getUserId(): int
    {
        return self::$userId ?: auth()->id();
    }

    public static function defaults(): array
    {
        return [
            'timezone' => 'UTC',
            'date_format' => config('linkace.default.date_format'),
            'time_format' => config('linkace.default.time_format'),
            'locale' => config('app.fallback_locale'),
            'profile_is_public' => false,
            'links_default_visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'notes_default_visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'lists_default_visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'archive_backups_enabled' => true,
            'archive_private_backups_enabled' => true,
            'listitem_count' => 24,
            'darkmode_setting' => 2,
            'link_display_mode' => Link::DISPLAY_LIST_DETAILED,
            'links_new_tab' => false,
            'markdown_for_text' => true,
            'share_services' => true,
        ];
    }
}
