<?php

namespace App\Audits\Modifiers;

class DisplayModeSettingModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return match ((int)$value) {
            0, 1 => trans('settings.display_mode_cards'),
            2 => trans('settings.display_mode_list_simple'),
            3 => trans('settings.display_mode_list_detailed'),
        };
    }
}
