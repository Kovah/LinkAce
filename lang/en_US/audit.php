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
        'user_settings' => [
            'api_token_generated' => 'User: API Token was generated',
            'api_token_revoken' => 'User: API Token was revoked',
        ],
    ],
];
