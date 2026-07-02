<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Veřejné',
        ModelAttribute::VISIBILITY_INTERNAL => 'Interní',
        ModelAttribute::VISIBILITY_PRIVATE => 'Soukromé',
    ],
];
