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

    'register' => 'Registrieren',
    'register_welcome' => 'Willkommen bei LinkAce! Sie wurden eingeladen, sich diesem Social Bookmarking Tool anzuschließen. Bitte wählen Sie einen Benutzernamen und ein Passwort. Nach der erfolgreichen Registrierung werden Sie zum Dashboard weitergeleitet.',

    'failed' => 'Diese Zugangsdaten stimmen nicht mit unseren Datensätzen überein.',
    'throttle' => 'Zu viele fehlgeschlagene Anmeldeversuche. Bitte versuche es erneut in :seconds Sekunden.',

    'confirm_title' => 'Bestätigung erforderlich',
    'confirm' => 'Bitte bestätige diese Aktion mit dem aktuellen Passwort.',
    'confirm_action' => 'Aktion bestätigen',

    'two_factor' => 'Zwei-Faktor-Authentifizierung',
    'two_factor_check' => 'Bitte geben jetzt das Einmalpasswort aus der Zwei-Faktor-Authentifizierungs-App ein.',
    'two_factor_with_recovery' => 'Mit Wiederherstellungscode authentifizieren',

    'api_tokens' => 'API-Token',
    'api_tokens.no_tokens_found' => 'Keine API-Token gefunden.',
    'api_tokens.generate' => 'Ein neues API Token generieren',
    'api_tokens.generate_short' => 'Token generieren',
    'api_tokens.generate_help' => 'API-Token werden verwendet, um sich bei der LinkAce API zu authentifizieren.',
    'api_tokens.generated_successfully' => 'Der API Token wurde erfolgreich generiert: <code>:token</code>',
    'api_tokens.generated_help' => 'Bitte speichern Sie diesen Token an einem sicheren Ort. Es ist <strong>nicht</strong> möglich, diesen Token wiederherzustellen, wenn Sie ihn verlieren.',
    'api_tokens.name' => 'Tokenname',
    'api_tokens.name_help' => 'Wählen Sie einen Namen für Ihren Token. Der Name darf nur alphanumerische Zeichen, Bindestriche und Unterstriche enthalten. Hilfreich, wenn Sie separate Token für verschiedene Anwendungsfälle oder Anwendungen erstellen möchten.',

    'api_token_system' => 'System API Token',
    'api_tokens_system' => 'System API Token',
    'api_tokens.generate_help_system' => 'API-Token werden verwendet, um von anderen Anwendungen oder Skripten auf die LinkAce API zuzugreifen. Standardmäßig sind nur öffentliche oder interne Daten zugänglich, Tokens können jedoch bei Bedarf zusätzlichen Zugriff auf private Daten erhalten.',
    'api_tokens.private_access' => 'Token kann auf private Daten zugreifen',
    'api_tokens.private_access_help' => 'Der Token kann auf private Links, Listen, Tags und Notizen eines Benutzers zugreifen und diese ändern.',
    'api_tokens.abilities' => 'Token-Berechtigungen',
    'api_tokens.abilities_select' => 'Token-Berechtigungen wählen...',
    'api_tokens.abilities_help' => 'Wähle alle Berechtigungen, die ein Token haben kann. Berechtigungen können später nicht geändert werden.',
    'api_tokens.ability_private_access' => 'Token kann auf private Daten zugreifen',

    'api_tokens.revoke' => 'Token widerrufen',
    'api_tokens.revoke_confirm' => 'Wollen Sie diesen Token wirklich widerrufen? Dieser Schritt kann nicht rückgängig gemacht werden und der Token kann nicht wiederhergestellt werden.',
    'api_tokens.revoke_successful' => 'Der Token wurde erfolgreich widerrufen.',

];
