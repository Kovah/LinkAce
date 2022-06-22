<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GuestSettings extends Settings
{
    public int $listitem_count;
    public int $link_display_mode;
    public bool $links_new_tab;
    public int $darkmode_setting;

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
        return 'guest';
    }
}
