<?php

namespace App\Audits\Modifiers;

use App\Enums\ModelAttribute;

class RedactedModifier implements ModifierInterface
{
    public function modify($value): string
    {
        $total = strlen($value);
        $leftOver = ceil($total / 5);

        // Make sure single character strings get redacted
        $length = ($total > $leftOver) ? ($total - $leftOver) : 1;

        return str_pad(substr($value, $length), $total, '#', STR_PAD_LEFT);
    }
}
