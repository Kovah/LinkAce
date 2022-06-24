<?php

namespace App\Rules;

use App\Enums\ModelAttribute;
use Illuminate\Contracts\Validation\Rule;

class ModelVisibility implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return in_array((int)$value, [
            ModelAttribute::VISIBILITY_PUBLIC,
            ModelAttribute::VISIBILITY_INTERNAL,
            ModelAttribute::VISIBILITY_PRIVATE,
        ], true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.visibility.visibility');
    }
}
