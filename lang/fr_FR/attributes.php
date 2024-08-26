<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Public',
        ModelAttribute::VISIBILITY_INTERNAL => 'Interne',
        ModelAttribute::VISIBILITY_PRIVATE => 'Privé',
    ],
];
