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

    'register' => 'Zaregistrovat se',
    'register_welcome' => 'Vítejte na LinkAce! Byli jste pozváni k registraci do této služby pro sdílení záložek. Zvolte si prosím uživatelské jméno a heslo. Po úspěšné registraci budete přesměrováni na hlavní stránku.',

    'failed' => 'Tyto přihlašovací údaje neodpovídají našim záznamům.',
    'throttle' => 'Příliš mnoho pokusů o přihlášení. Zkuste to prosím znovu za :seconds sekund.',
    'unauthorized' => 'Přihlášení není oprávněné. Obraťte se prosím na správce.',

    'confirm_title' => 'Vyžaduje se potvrzení',
    'confirm' => 'Potvrďte prosím tuto akci pomocí svého aktuálního hesla.',
    'confirm_action' => 'Potvrdit akci',

    'two_factor' => 'Dvoufázové ověření',
    'two_factor_check' => 'Zadejte prosím jednorázové heslo, které vám poskytla aplikace dvoufaktorového ověřování.',
    'two_factor_with_recovery' => 'Ověření pomocí kódu pro obnovení',

    'api_tokens' => 'API tokeny',
    'api_tokens.no_tokens_found' => 'Nebyly nalezeny žádné API tokeny.',
    'api_tokens.generate' => 'Generovat nový API Token',
    'api_tokens.generate_short' => 'Vygenerovat token',
    'api_tokens.generate_help' => 'API tokeny slouží k ověření identity při používání rozhraní API LinkAce.',
    'api_tokens.generated_successfully' => 'API token byl úspěšně vygenerován: <code>:token</code>',
    'api_tokens.generated_help' => 'Tento token prosím uložte na bezpečném místě. V případě ztráty tokenu <strong>není</strong> možné jej obnovit.',
    'api_tokens.name' => 'Název tokenu',
    'api_tokens.name_help' => 'Zvolte název pro svůj token. Název může obsahovat pouze alfanumerické znaky, pomlčky a podtržítka. To se hodí, pokud chcete vytvořit samostatné tokeny pro různé případy použití nebo aplikace.',

    'api_token_system' => 'Systémový API Token',
    'api_tokens_system' => 'Systémový API Token',
    'api_tokens.generate_help_system' => 'API tokeny slouží k přístupu k rozhraní API LinkAce z jiných aplikací nebo skriptů. Ve výchozím nastavení jsou přístupná pouze veřejná nebo interní data, v případě potřeby však lze tokenům udělit další oprávnění k přístupu k soukromým datům.',
    'api_tokens.private_access' => 'Token má přístup k soukromým datům',
    'api_tokens.private_access_help' => 'Token má na základě zadaných oprávnění přístup k soukromým odkazům, seznamům, štítkům a poznámkám kteréhokoli uživatele a může je měnit.',
    'api_tokens.abilities' => 'Možnosti tokenů',
    'api_tokens.abilities_select' => 'Vyberte možnosti tokenů...',
    'api_tokens.abilities_help' => 'Vyberte všechny možnosti, které token může mít. Možnosti nelze později změnit.',
    'api_tokens.ability_private_access' => 'Token má přístup k soukromým datům',

    'api_tokens.revoke' => 'Zneplatnit token',
    'api_tokens.revoke_confirm' => 'Opravdu chcete tento token zneplatnit? Tento krok nelze vrátit zpět a token nelze obnovit.',
    'api_tokens.revoke_successful' => 'Token byl úspěšně zneplatněn.',

    'sso' => 'SSO',
    'sso_account_provider' => 'Poskytovatel SSO',
    'sso_account_id' => 'SSO ID',
    'sso_provider_disabled' => 'Vybraný poskytovatel SSO není k dispozici. Vyberte prosím jiného.',
    'sso_wrong_provider' => 'Nelze se přihlásit pomocí :currentProvider. K přihlášení prosím použijte :userProvider nebo se obraťte na správce systému.',

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
