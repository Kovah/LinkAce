<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Openbaar',
        ModelAttribute::VISIBILITY_INTERNAL => 'Intern',
        ModelAttribute::VISIBILITY_PRIVATE => 'Privé',
    ],
];
