<?php
return [
    'default' => [
        'pagination' => 25,
        'date_format' => 'Y-m-d',
        'time_format' => 'H:i',
        'cache_duration' => 60, // minutes
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
