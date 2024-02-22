<?php

namespace App\Audits\Modifiers;

class DarkmodeSettingModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return match ((int)$value) {
            0 => trans('settings.darkmode_disabled'),
            1 => trans('settings.darkmode_permanent'),
            2 => trans('settings.darkmode_auto'),
        };
    }
}
