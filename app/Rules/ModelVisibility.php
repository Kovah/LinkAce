<?php

namespace App\Rules;

use App\Enums\ModelAttribute;
use Illuminate\Contracts\Validation\Rule;

class ModelVisibility implements Rule
{
    public function passes($attribute, $value): bool
    {
        return in_array((int)$value, [
            ModelAttribute::VISIBILITY_PUBLIC,
            ModelAttribute::VISIBILITY_INTERNAL,
            ModelAttribute::VISIBILITY_PRIVATE,
        ], true);
    }

    public function message(): string
    {
        return trans('validation.custom.visibility.visibility');
    }
}
