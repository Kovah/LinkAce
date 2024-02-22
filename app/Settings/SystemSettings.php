<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public ?string $page_title;
    public ?string $cron_token;
    public ?string $custom_header_content;

    public bool $guest_access_enabled;
    public bool $setup_completed;

    public static function group(): string
    {
        return 'system';
    }
}
