<?php

return [

    'log' => 'Protokol auditu',
    'system_events' => 'Systémové události',
    'settings_history' => 'Historie nastavení',
    'user_history' => 'Historie uživatelů',
    'user_history_entry' => 'Uživatel :id: :change',

    'no_logs_found' => 'Nebyly nalezeny žádné protokoly',

    'activity_entry_with_causer' => ':change provedl :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'Systém: Cron Token byl znovu generován',
        ],
        'user_settings' => [
            'api_token_generated' => 'Uživatel: API Token byl vygenerován',
            'api_token_revoken' => 'Uživatel: API Token byl zneplatněn',
        ],
    ],
];
