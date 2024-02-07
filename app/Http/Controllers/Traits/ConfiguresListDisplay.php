<?php

namespace App\Http\Controllers\Traits;

use App\Models\Link;
use App\Settings\UserSettings;

trait ConfiguresListDisplay
{
    public function saveNewListDisplay(): void
    {
        $newSetting = request()?->input('list-type');
        if ($newSetting === null) {
            return;
        }

        if (in_array($newSetting, [Link::DISPLAY_LIST_SIMPLE, Link::DISPLAY_LIST_DETAILED, Link::DISPLAY_CARDS])) {
            $userSettings = app(UserSettings::class);
            $userSettings->link_display_mode = $newSetting;
        }
    }
}
