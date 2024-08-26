<?php
return [
    'settings' => '設定',
    'user_settings' => 'ユーザー設定',
    'account_settings' => 'アカウント設定',
    'app_settings' => 'アプリケーション設定',
    'system_settings' => 'システム設定',
    'guest_settings' => 'ゲスト設定',

    'locale' => '言語',
    'timezone' => 'タイムゾーン',
    'date_format' => '日付書式',
    'time_format' => '時刻形式',
    'listitem_count' => 'リスト内のアイテム数',

    'links_new_tab' => '外部リンクを新しいタブで開く',

    'markdown_for_text' => '説明とメモのマークダウンを有効にする',

    'privacy' => 'プライバシー',
    'profile_privacy' => 'ゲストに表示されるユーザープロファイルには、次の設定が適用されます。',
    'profile_is_public' => 'プロファイルは公開されています',
    'default_visibility_help' => '以下の設定では、新しいリンクやリスト、メモ、タグを追加するときのデフォルトの公開範囲を定義します。',
    'links_default_visibility' => 'リンクのデフォルト公開範囲',
    'notes_default_visibility' => 'ノートのデフォルト公開範囲',
    'lists_default_visibility' => 'リストのデフォルト公開範囲',
    'tags_default_visibility' => 'タグのデフォルト公開範囲',

    'archive_backups' => 'Wayback Machine バックアップ',
    'archive_backups_help' => '有効にすると、LinkAceは<a href="https://archive.org/">Wayback Machine</a>にリンクをバックアップするよう要求します。Wayback Machineは非営利団体であるInternet Archiveによって運営されています。Internet Archiveへの<a href="https://archive.org/donate/">寄付</a>をご検討ください。',
    'archive_backups_enabled' => 'バックアップを有効化',
    'archive_backups_enabled_help' => '有効にすると、公開リンクはインターネットアーカイブによって保存されます。',
    'archive_private_backups_enabled' => '非公開リンクのバックアップを有効化',
    'archive_private_backups_enabled_help' => '有効にした場合、非公開リンクも保存されます。バックアップを有効にする必要があります。',

    'link_display_mode' => 'リンク表示モード',
    'display_mode' => 'リンクの表示方法',
    'display_mode_list_simple' => '簡潔なリスト表示でリンクを表示',
    'display_mode_list_detailed' => '詳細なリスト表示でリンクを表示',
    'display_mode_cards' => 'カード表示でリンクを表示する',

    'sharing' => 'リンク共有',
    'guest_sharing' => 'ゲストリンクの共有',
    'sharing_help' => 'リンクに表示したいすべてのサービスを有効にし、ワンクリックで簡単に共有できるようにします。',
    'sharing_toggle' => 'すべてのオン/オフを切り替える',

    'darkmode_setting' => 'ダークモード',
    'darkmode_help' => '永続的にオンにするか、デバイス設定に従い自動的にオンにするか選択できます。(<small>Check <a href="https://caniuse.com/#search=prefers-color-scheme">ここ</a>であなたのブラウザが自動検出に対応しているか確認できます</small>)',
    'darkmode_disabled' => '無効',
    'darkmode_auto' => '自動',
    'darkmode_permanent' => '永続的',

    'save_settings' => '設定を保存',
    'settings_saved' => '設定の更新に成功しました！',

    'bookmarklet' => 'ブックマークレット',
    'bookmarklet_button' => 'ブックマークにドラッグして追加するか、右クリックしてブックマークとして保存してください。',
    'bookmarklet_help' => 'このブックマークレットをブラウザに追加すると、LinkAceを手動で開かなくても訪問したサイトからリンクをすばやく追加できます。',

    'change_password' => 'パスワードを変更',
    'old_password' => '古いパスワード',
    'new_password' => '新しいパスワード',
    'new_password2' => '新しいパスワードを再入力',
    'password_updated' => 'パスワードの変更に成功しました！',
    'old_password_invalid' => '古いパスワードが正しくありません！',

    'two_factor_auth' => '2要素認証',
    'two_factor_enable' => '2要素認証を有効化',
    'two_factor_disable' => '2要素認証を無効化',
    'two_factor_setup_app' => '2要素認証が有効になりました。次のQRコードをスキャンして認証デバイスを設定してください。',
    'two_factor_setup_url' => 'QRコードが動作しませんか？このURLを直接使用することもできます。',
    'two_factor_recovery_codes' => 'これらのリカバリーコードを安全なパスワードマネージャーに保存してください。2要素認証デバイスが失われた場合に、あなたのアカウントへのアクセスを回復するために使用できます。',
    'two_factor_recovery_codes_view' => 'リカバリーコードを表示',
    'two_factor_regenerate_recovery_codes' => '新しいリカバリーコードを生成する',

    'page_title' => 'ページタイトル',
    'guest_access' => 'ゲストアクセスを有効化',
    'guest_access_help' => '有効にすると、ゲストは非公開ではないすべてのリンクを見ることができます。',
    'custom_header_content' => 'カスタムヘッダーコンテンツ',
    'custom_header_content_help' => 'ここに入力したコンテンツはすべてのLinkAceサイトの &lt;/head&gt; タグの前に配置されます。 分析やカスタムスクリプトを配置するのに便利です。注意：コンテンツはエスケープされていないと、サイトが壊れる可能性があります！',

    'cron_token' => 'Cronトークン',
    'cron_token_generate' => 'トークンを生成',
    'cron_token_generate_confirm' => '本当に新しいトークンを生成しますか？',
    'cron_token_help' => 'cronトークンは、デッドリンクの有無やバックアップの実行を確認するcronサービスを実行するために必要です。',
    'cron_token_url' => '次のURLにあなたのcronを向けてください：<span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => '注意：すでにcronトークンを持っている場合、新しいトークンを生成すると既存のcronジョブが壊れます！',
    'cron_token_generate_failure' => '新しいAPIトークンを生成できませんでした。ブラウザのコンソールとアプリケーションのログを確認してください。',
    'cron_token_auth_failure' => '指定されたcronトークンは無効です',
    'cron_execute_successful' => 'Cronが正常に実行されました',

    'update_check' => '更新確認',
    'update_check_running' => '更新を確認しています…',
    'update_check_version_found' => '更新が見つかりました。バージョン #VERSION# が利用可能です。',
    'update_check_success' => '更新はありませんでした。',
    'update_check_failed' => '更新を確認できませんでした。',

    'guest_settings_info' => 'ゲストアクセスが有効になっている場合、以下の設定はあなたのサイトを訪問したゲストに適用されます。',
];
