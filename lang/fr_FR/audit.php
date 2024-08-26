<?php

return [

    'log' => 'Journal d\'audit',
    'system_events' => 'Événements Système',
    'settings_history' => 'Historique des paramètres',
    'user_history' => 'Historique Utilisateur',
    'user_history_entry' => 'Utilisateur :id: :change',

    'no_logs_found' => 'Aucun log trouvé',

    'activity_entry_with_causer' => ':change par :causer',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'Système : le Jeton Cron a été régénéré',
        ],
        'user_settings' => [
            'api_token_generated' => 'Utilisateur : le Jeton API a été généré',
            'api_token_revoken' => 'Utilisateur : le Jeton API a été révoqué',
        ],
    ],
];
