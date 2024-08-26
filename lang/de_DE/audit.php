<?php

return [

    'log' => 'Audit-Protokoll',
    'system_events' => 'System-Ereignisse',
    'settings_history' => 'Einstellungsverlauf',
    'user_history' => 'Benutzerverlauf',
    'user_history_entry' => 'Benutzer :id: :change',

    'no_logs_found' => 'Keine Protokolle gefunden',

    'activity_entry_with_causer' => ':change von :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'System: Cron-Token wurde neu generiert',
        ],
        'user_settings' => [
            'api_token_generated' => 'Benutzer: API Token wurde generiert',
            'api_token_revoken' => 'Benutzer: API Token wurde widerrufen',
        ],
    ],
];
