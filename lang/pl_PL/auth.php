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

    'register' => 'Zarejestruj się',
    'register_welcome' => 'Witaj w LinkAce! Zostałeś zaproszony do dołączenia do tego narzędzia społecznościowego. Wybierz nazwę użytkownika i hasło. Po pomyślnej rejestracji zostaniesz przekierowany do panelu głównego.',

    'failed' => 'Podane dane logowania są nieprawidłowe.',
    'throttle' => 'Zbyt wiele nieudanych prób logowania. Spróbuj ponownie za :seconds sekund.',
    'unauthorized' => 'Brak autoryzacji. Skontaktuj się z administratorem.',

    'confirm_title' => 'Wymagane potwierdzenie',
    'confirm' => 'Potwierdź tę czynność, używając swojego aktualnego hasła.',
    'confirm_action' => 'Potwierdź czynność',

    'two_factor' => 'Uwierzytelnianie dwuskładnikowe',
    'two_factor_check' => 'Wprowadź jednorazowe hasło wygenerowane przez aplikację uwierzytelniającą.',
    'two_factor_with_recovery' => 'Uwierzytelnianie przy użyciu kodu odzyskiwania',

    'api_tokens' => 'Tokeny API',
    'api_tokens.no_tokens_found' => 'Nie znaleziono żadnych tokenów API.',
    'api_tokens.generate' => 'Wygeneruj nowy token API',
    'api_tokens.generate_short' => 'Wygeneruj token',
    'api_tokens.generate_help' => 'Tokeny API służą do uwierzytelniania podczas korzystania z API LinkAce.',
    'api_tokens.generated_successfully' => 'Token API został pomyślnie wygenerowany: <code>:token</code>',
    'api_tokens.generated_help' => 'Zachowaj ten token w bezpiecznym miejscu. <strong>Nie</strong> będzie możliwości jego odzyskania w przypadku utraty.',
    'api_tokens.name' => 'Nazwa tokena',
    'api_tokens.name_help' => 'Podaj nazwę swojego tokena. Nazwa może zawierać jedynie znaki alfanumeryczne, myślniki i podkreślenia. Przydatne, jeśli chcesz tworzyć oddzielne tokeny do różnych zastosowań lub aplikacji.',

    'api_token_system' => 'Token API systemowy',
    'api_tokens_system' => 'Tokeny API systemowe',
    'api_tokens.generate_help_system' => 'Tokeny API umożliwiają dostęp do API LinkAce z innych aplikacji lub skryptów. Domyślnie pozwalają na dostęp do danych publicznych i wewnętrznych, ale można im przyznać także uprawnienia do danych prywatnych.',
    'api_tokens.private_access' => 'Token może uzyskiwać dostęp do danych prywatnych',
    'api_tokens.private_access_help' => 'Token może przeglądać i modyfikować prywatne linki, listy, tagi i notatki dowolnego użytkownika w zależności od przyznanych uprawnień.',
    'api_tokens.abilities' => 'Uprawnienia tokena',
    'api_tokens.abilities_select' => 'Wybierz uprawnienia tokena...',
    'api_tokens.abilities_help' => 'Zaznacz wszystkie uprawnienia, które ma posiadać token. Po zapisaniu nie będzie można ich zmienić.',
    'api_tokens.ability_private_access' => 'Token może uzyskiwać dostęp do danych prywatnych',

    'api_tokens.revoke' => 'Cofnij token',
    'api_tokens.revoke_confirm' => 'Czy na pewno chcesz cofnąć ten token? Operacji nie można odwrócić, a token nie będzie możliwy do odzyskania.',
    'api_tokens.revoke_successful' => 'Token został pomyślnie cofnięty.',

    'sso' => 'SSO',
    'sso_account_provider' => 'Dostawca SSO',
    'sso_account_id' => 'Identyfikator SSO',
    'sso_provider_disabled' => 'Wybrany dostawca SSO jest niedostępny. Wybierz innego.',
    'sso_wrong_provider' => 'Nie można zalogować się za pomocą :currentProvider. Użyj :userProvider, aby się zalogować, lub skontaktuj się z administratorem.',

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
