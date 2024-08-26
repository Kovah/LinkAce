<?php

return [

    'log' => '監査ログ',
    'system_events' => 'システムイベント',
    'settings_history' => '設定履歴',
    'user_history' => 'ユーザー履歴',
    'user_history_entry' => 'ユーザー :id: :change',

    'no_logs_found' => 'ログが見つかりません',

    'activity_entry_with_causer' => ':causer によって :change',

    'logs' => [
        'system' => [
            'cron_token_regenerated' => 'システム：Cronトークンが再生成されました',
        ],
        'user_settings' => [
            'api_token_generated' => 'ユーザー：APIトークンが生成されました',
            'api_token_revoken' => 'ユーザー：APIトークンが取り消されました',
        ],
    ],
];
