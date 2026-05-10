<?php

use App\Enums\ModelAttribute;

return [
    'visibility' => [
        ModelAttribute::VISIBILITY_PUBLIC => 'Публічне',
        ModelAttribute::VISIBILITY_INTERNAL => 'Внутрішнє',
        ModelAttribute::VISIBILITY_PRIVATE => 'Приватне',
    ],
];
