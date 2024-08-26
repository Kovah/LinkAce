<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'register' => '登録',
    'register_welcome' => 'Linkaceへようこそ！あなたはこのソーシャルブックマークツールに招待されています。ユーザー名とパスワードを入力してください。登録が完了するとダッシュボードに転送されます。',

    'failed' => '資格情報が記録と一致しません。',
    'throttle' => 'ログイン試行回数が多すぎます。:seconds 秒後にもう一度お試しください。',

    'confirm_title' => '確認が必要です',
    'confirm' => '現在のパスワードを使用してこの操作を確認してください。',
    'confirm_action' => 'アクションの確認',

    'two_factor' => '2要素認証',
    'two_factor_check' => '2段階認証アプリに表示されているワンタイムパスワードを入力してください。',
    'two_factor_with_recovery' => 'リカバリーコードで認証',

    'api_tokens' => 'APIトークン',
    'api_tokens.no_tokens_found' => 'APIトークンが見つかりません。',
    'api_tokens.generate' => '新しいAPIトークンを生成する',
    'api_tokens.generate_short' => 'トークンを生成',
    'api_tokens.generate_help' => 'APIトークンはLinkAceのAPIを使用する際の認証に使用されます。',
    'api_tokens.generated_successfully' => 'APIトークンの生成に成功しました：<code>:token</code>',
    'api_tokens.generated_help' => 'このトークンは安全な場所に保管してください。トークンを忘れた場合、復活させることは<strong>できません</strong>。',
    'api_tokens.name' => 'トークン名',
    'api_tokens.name_help' => 'トークン名を選択します。名前には英数字、ダッシュ、アンダースコアのみを含めることができます。異なるユースケースやアプリケーション用に個別のトークンを作成したい場合に役立ちます。',

    'api_token_system' => 'システムAPIトークン',
    'api_tokens_system' => 'システムAPIトークン',
    'api_tokens.generate_help_system' => 'APIトークンは他のアプリケーションやスクリプトからLinkAce APIにアクセスするために使用されます。デフォルトでは、公開データまたは内部データのみがアクセス可能ですが、必要に応じてトークンに追加のアクセス権を付与することができます。',
    'api_tokens.private_access' => 'トークンは非公開データにアクセスできます',
    'api_tokens.private_access_help' => '指定された権限に基づいて、任意のユーザーの非公開リンク、リスト、タグ、ノートにアクセスおよび変更を行います。',
    'api_tokens.abilities' => 'トークン権限',
    'api_tokens.abilities_select' => 'トークン権限を選択',
    'api_tokens.abilities_help' => 'トークンが持つことができるすべての権限を選択してください。権限は後で変更できません。',
    'api_tokens.ability_private_access' => 'トークンは非公開データにアクセスできます',

    'api_tokens.revoke' => 'トークンを取り消す',
    'api_tokens.revoke_confirm' => '本当にこのトークンを取り消しますか？このステップは取り消せず、トークンは復元できません。',
    'api_tokens.revoke_successful' => 'トークンは正常に取り消されました。',

];
