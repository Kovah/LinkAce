<?php

namespace App\Audits\Modifiers;

interface ModifierInterface
{
    /**
     * Modify an attribute value.
     *
     * @param mixed $value
     *
     * @return null|string
     */
    public function modify(mixed $value): ?string;
}
