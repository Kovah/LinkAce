<?php

namespace App\Audits\Modifiers;

class LocaleSettingModifier implements ModifierInterface
{
    public function modify($value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Fall back to the raw value for locales which are no longer or were
        // never available, as audit entries must always be displayable
        return config('app.available_locales.' . $value) ?? (string) $value;
    }
}
