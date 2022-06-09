<?php

namespace App\Audits\Modifiers;

use App\Models\Tag;

class TagRelationModifier implements ModifierInterface
{
    public function modify($value): ?string
    {
        return $value ? Tag::whereIn('id', $value)->pluck('name')->join(', ') : null;
    }
}
