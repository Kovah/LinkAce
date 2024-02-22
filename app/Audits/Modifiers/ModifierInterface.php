<?php

namespace App\Audits\Modifiers;

interface ModifierInterface
{
    public function modify(mixed $value): ?string;
}
