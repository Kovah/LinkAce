<?php

return [

    'log' => 'Auditlogboek',
    'system_events' => 'Systeemgebeurtenissen',
    'settings_history' => 'Instellingengeschiedenis',
    'user_history' => 'Gebruikersgeschiedenis',
    'user_history_entry' => 'Wijziging gebruiker :id:',

    'no_logs_found' => 'Geen logboekvermeldingen gevonden',

    'activity_entry_with_causer' => ':change door :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'Systeem: Cron-token is opnieuw gegenereerd',
        ],
        'user_settings' => [
            'api_token_generated' => 'Gebruiker: API-token is gegenereerd',
            'api_token_revoken' => 'Gebruiker: API-token is ingetrokken',
        ],
    ],
];
