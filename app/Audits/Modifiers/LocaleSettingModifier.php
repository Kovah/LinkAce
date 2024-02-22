<?php

namespace App\Audits\Modifiers;

class LocaleSettingModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return config('app.available_locales.' . $value);
    }
}
