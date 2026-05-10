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

    'register' => 'Реєстрація',
    'register_welcome' => 'Ласкаво просимо до LinkAce! Вас запрошено приєднатися до цього інструменту соціальних закладок. Будь ласка, виберіть ім\'я користувача та пароль. Після успішної реєстрації ви будете перенаправлені на інформаційну панель.',

    'failed' => 'Ці облікові дані не збігаються з нашими записами.',
    'throttle' => 'Занадто багато спроб входу. Будь ласка, спробуйте ще раз, через :seconds секунд.',
    'unauthorized' => 'Неавторизований вхід. Зверніться до адміністратора.',

    'confirm_title' => 'Потрібне підтвердження',
    'confirm' => 'Будь ласка, підтвердіть цю дію, використовуючи ваш поточний пароль.',
    'confirm_action' => 'Підтвердити дію',

    'two_factor' => 'Двофакторна аутентифікація',
    'two_factor_check' => 'Будь ласка, введіть одноразовий пароль, наданий вашим додатком для двофакторної автентифікації.',
    'two_factor_with_recovery' => 'Автентифікація за допомогою коду відновлення',

    'api_tokens' => 'API токени',
    'api_tokens.no_tokens_found' => 'API токени не знайдено.',
    'api_tokens.generate' => 'Згенерувати новий API токен',
    'api_tokens.generate_short' => 'Згенерувати токен',
    'api_tokens.generate_help' => 'API токени використовуються для автентифікації при використанні LinkAce API.',
    'api_tokens.generated_successfully' => 'API токен згенеровано успішно: <code>:token</code>',
    'api_tokens.generated_help' => 'Будь ласка, зберігайте цей токен у безпечному місці. У разі втрати токену його <strong>неможливо</strong> відновити.',
    'api_tokens.name' => 'Ім\'я токену',
    'api_tokens.name_help' => 'Виберіть ім\'я для вашого токена. Ім\'я може містити лише буквено-цифрові символи, тире та підкреслення. Корисно, якщо ви хочете створити окремі токени для різних сценаріїв використання або додатків.',

    'api_token_system' => 'Системний API токен',
    'api_tokens_system' => 'Системні API токени',
    'api_tokens.generate_help_system' => 'Токени API використовуються для доступу до LinkAce API з інших програм або скриптів. За замовчуванням доступні лише публічні або внутрішні дані, але за потреби токенам можна надати додатковий доступ до приватних даних.',
    'api_tokens.private_access' => 'Токен може отримати доступ до приватних даних',
    'api_tokens.private_access_help' => 'Токен може отримувати доступ та змінювати приватні посилання, списки, теги та нотатки будь-якого користувача на основі заданих можливостей.',
    'api_tokens.abilities' => 'Можливості токенів',
    'api_tokens.abilities_select' => 'Виберіть можливості токена...',
    'api_tokens.abilities_help' => 'Виберіть всі можливості, які може мати токен. Можливості не можуть бути змінені пізніше.',
    'api_tokens.ability_private_access' => 'Токен може отримати доступ до приватних даних',

    'api_tokens.revoke' => 'Відкликати токен',
    'api_tokens.revoke_confirm' => 'Ви дійсно хочете відкликати цей токен? Цей крок не можна скасувати і токен не можна відновити.',
    'api_tokens.revoke_successful' => 'Токен було успішно відкликано.',

    'sso' => 'SSO',
    'sso_account_provider' => 'SSO-провайдер',
    'sso_account_id' => 'SSO ID',
    'sso_provider_disabled' => 'Обраний провайдер SSO недоступний. Будь ласка, виберіть іншого.',
    'sso_wrong_provider' => 'Не вдалося увійти за допомогою :currentProvider. Будь ласка, використовуйте :userProvider для входу або зверніться за допомогою до вашого адміністратора.',

    'sso_provider' => [
        'auth0' => 'Auth0',
        'authentik' => 'Authentik',
        'azure' => 'Azure',
        'cognito' => 'Cognito',
        'fusionauth' => 'FusionAuth',
        'google' => 'Google',
        'github' => 'GitHub',
        'gitlab' => 'GitLab',
        'keycloak' => 'Keycloak',
        'oidc' => 'OIDC',
        'okta' => 'Okta',
        'zitadel' => 'Zitadel',
    ],
];
