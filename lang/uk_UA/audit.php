<?php

return [

    'log' => 'Журнал аудиту',
    'system_events' => 'Системні події',
    'settings_history' => 'Історія налаштувань',
    'user_history' => 'Історія користувача',
    'user_history_entry' => 'Користувач :id: :change',

    'no_logs_found' => 'Журналів не знайдено',

    'activity_entry_with_causer' => ':change на :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'Система: Cron Token було згенеровано повторно',
        ],
        'user_settings' => [
            'api_token_generated' => 'Користувач: API-токен згенеровано',
            'api_token_revoken' => 'Користувач: Токен API відкликано',
        ],
    ],
];
