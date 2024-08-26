<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Öffentlich',
        ModelAttribute::VISIBILITY_INTERNAL => 'Intern',
        ModelAttribute::VISIBILITY_PRIVATE => 'Privat',
    ],
];
