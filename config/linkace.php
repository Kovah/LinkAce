<?php
return [
    'default' => [
        'pagination' => 25,
        'date_format' => 'Y-m-d',
        'time_format' => 'H:i',
        'cache_duration' => 3600, // 60 minutes
    ],

    // Maximum number of items a guest request may ask for via ?per_page=
    'max_pagination' => 200,

    // Requests per minute allowed on the public guest routes
    'guest_rate_limit' => (int) env('GUEST_RATE_LIMIT', 60),

    'link_checks' => [
        // Number of weeks between re-checks of broken links
        'broken_recheck_interval_weeks' => (int) env('BROKEN_LINK_RECHECK_INTERVAL_WEEKS', 2),
    ],

    'search' => [
        'driver' => env('APP_SEARCH_DRIVER', env('SCOUT_DRIVER', 'database')),
        'supported_drivers' => ['database', 'meilisearch', 'typesense'],
        'external_drivers' => ['meilisearch', 'typesense'],
    ],

    'listitem_count_values' => [
        12,
        24,
        60,
        72,
        120,
    ],

    'formats' => [
        'date' => [
            'Y-m-d',
            'Y/m/d',
            'Y-m-d',
            'd.m.Y',
            'd/m/Y',
            'd-m-Y',
            'm/d/Y',
            'm-d-Y',
            'm.d.Y',
            'j.n.Y',
        ],
        'time' => [
            'H:i',
            'h:i a',
            'h:i A',
            'G:i',
            'g:i a',
            'g:i A',
        ],
    ],
];
