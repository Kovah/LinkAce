<?php

namespace App\Audits\Modifiers;

use App\Enums\ModelAttribute;

class VisibilityModifier implements ModifierInterface
{
    public function modify($value): string
    {
        return match ($value) {
            ModelAttribute::VISIBILITY_PUBLIC => trans('attributes.visibility.' . ModelAttribute::VISIBILITY_PUBLIC),
            ModelAttribute::VISIBILITY_INTERNAL => trans('attributes.visibility.' . ModelAttribute::VISIBILITY_INTERNAL),
            ModelAttribute::VISIBILITY_PRIVATE => trans('attributes.visibility.' . ModelAttribute::VISIBILITY_PRIVATE),
        };
    }
}
