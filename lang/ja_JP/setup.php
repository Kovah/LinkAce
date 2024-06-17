<?php

return [
    'setup' => 'セットアップ',
    'continue' => '続行',
    'try_again' => '再試行',

    'welcome' => 'LinkAceのセットアップへようこそ',
    'intro' => '次の手順では、LinkAceを使用できるように設定します。',
    'intro.step1' => 'すべての要件を満たしているか確認します。',
    'intro.step2' => 'データベースをセットアップし、接続に成功するか確認します。',
    'intro.step3' => 'ユーザーアカウントを作成',

    'setup_requirements' => 'セットアップ要件',
    'check_requirements' => '要件の確認',
    'requirements.php_version' => 'PHPバージョン >= 7.4.0',
    'requirements.extension_bcmath' => 'PHP拡張モジュール：BCMath',
    'requirements.extension_ctype' => 'PHP拡張モジュール：Ctype',
    'requirements.extension_json' => 'PHP拡張モジュール：JSON',
    'requirements.extension_mbstring' => 'PHP拡張モジュール：Mbstring',
    'requirements.extension_openssl' => 'PHP拡張モジュール：OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP拡張モジュール：PDO MySQL',
    'requirements.extension_tokenizer' => 'PHP拡張モジュール：Tokenizer',
    'requirements.extension_xml' => 'PHP拡張モジュール：XML',
    'requirements.env_writable' => '.envファイルが存在し、書き込み可能',
    'requirements.storage_writable' => '/storageおよび/storage/logsディレクトリが書き込み可能',

    'database_configuration' => 'データベース構成',
    'database_configure' => 'データベースを構成',
    'database.intro' => '.envファイルにデータベースの情報を入力している場合、入力フィールドに事前入力されています。それ以外の場合、データベースの情報を各フィールドに入力してください。',
    'database.config_error' => 'データベースを構成できませんでした。接続の詳細を確認してください。詳細：',
    'database.db_host' => 'データベースのホスト',
    'database.db_port' => 'データベースのポート',
    'database.db_name' => 'データベースの名前',
    'database.db_user' => 'データベースのユーザー名',
    'database.db_password' => 'データベースのパスワード',
    'database.complete_hint' => 'データベースの構成を保存し、アプリを使用するために準備するには数秒かかる場合がありますので、しばらくお待ちください。',

    'database.data_present' => '注意！指定したデータベースからデータが見つかりました！そのデータベースのバックアップがあることを確認し、すべてのデータの削除を確認してください。',
    'database.overwrite_data' => 'すべてのデータを削除し、新しいLinkAceデータベースで上書きする必要があることを確認します。',

    'account_setup' => 'アカウントのセットアップ',
    'account_setup.intro' => '開始する前に、ユーザーアカウントを作成する必要があります。',
    'account_setup.name' => '名前を入力',
    'account_setup.email' => 'メールアドレスを入力',
    'account_setup.password' => '強力なパスワードを入力',
    'account_setup.password_requirements' => '最小文字数：10文字',
    'account_setup.password_confirmed' => 'パスワードを再入力',
    'account_setup.create' => 'アカウントを作成',

    'complete' => 'セットアップが完了しました！',
    'outro' => 'セットアップを完了し、LinkAceを使用できるようになりました！ログインしてすぐにブックマークを開始できます。',
];
