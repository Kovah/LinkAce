<?php

namespace App\Audits\Modifiers;

class BooleanModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return $value ? trans('linkace.yes') : trans('linkace.no');
    }
}
