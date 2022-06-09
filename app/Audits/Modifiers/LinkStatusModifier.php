<?php

namespace App\Audits\Modifiers;

class LinkStatusModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return trans('link.stati.' . $value);
    }
}
