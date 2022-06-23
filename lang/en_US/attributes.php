<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Public',
        ModelAttribute::VISIBILITY_INTERNAL => 'Internal',
        ModelAttribute::VISIBILITY_PRIVATE => 'Private',
    ],
];
