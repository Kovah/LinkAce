<?php

return [

    'log' => 'Dziennik zdarzeń',
    'system_events' => 'Zdarzenia systemowe',
    'settings_history' => 'Historia ustawień',
    'user_history' => 'Historia użytkownika',
    'user_history_entry' => 'Użytkownik :id: :change',

    'no_logs_found' => 'Nie znaleziono dzienników',

    'activity_entry_with_causer' => ':change przez :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'System: Token Cron został ponownie wygenerowany',
        ],
        'user_settings' => [
            'api_token_generated' => 'Użytkownik: Token API został wygenerowany',
            'api_token_revoken' => 'Użytkownik: Token API został cofnięty',
        ],
    ],
];
