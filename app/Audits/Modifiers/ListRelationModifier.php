<?php

namespace App\Audits\Modifiers;

use App\Models\LinkList;

class ListRelationModifier implements ModifierInterface
{
    public function modify($value): ?string
    {
        return $value ? LinkList::whereIn('id', $value)->pluck('name')->join(', ') : null;
    }
}
