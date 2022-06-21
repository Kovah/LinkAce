<?php

return [

    'log' => 'Audit Log',
    'system_events' => 'System Events',
    'settings_history' => 'Settings History',
    'user_history' => 'User History',
    'user_history_entry' => 'User :id: :change',

    'no_logs_found' => 'No logs found',

    'activity_entry_with_causer' => ':change by :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'System: Cron Token was re-generated',
        ],
        'user' => [
            'api_token_regenerated' => 'User: API Token was re-generated',
        ],
    ],
];
