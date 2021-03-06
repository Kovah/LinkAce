<?php
return [
    'settings' => 'Einstellungen',
    'user_settings' => 'Benutzereinstellungen',
    'account_settings' => 'Kontoeinstellungen',
    'app_settings' => 'Anwendungseinstellungen',
    'system_settings' => 'Systemeinstellungen',
    'guest_settings' => 'Gast-Einstellungen',

    'language' => 'Sprache',
    'timezone' => 'Zeitzone',
    'date_format' => 'Datumsformat',
    'time_format' => 'Zeitformat',
    'listitem_count' => 'Anzahl der Elemente in Listen',

    'links_new_tab' => 'Externe Links in neuen Tabs öffnen',

    'markdown_for_text' => 'Markdown für Beschreibungen und Notizen aktivieren',

    'privacy' => 'Privatsphäre',
    'links_private_default' => 'Standardmäßig private Links',
    'links_private_default_help' => 'Aktivieren wird alle neuen Links standardmäßig privat machen',
    'notes_private_default' => 'Standardmäßig private Notizen',
    'notes_private_default_help' => 'Aktivieren macht alle neuen Notizen standardmäßig privat',
    'tags_private_default' => 'Standardmäßig private Tags',
    'tags_private_default_help' => 'Aktivieren macht alle neuen Tags standardmäßig privat',
    'lists_private_default' => 'Standardmäßig private Listen',
    'lists_private_default_help' => 'Aktivieren macht alle neuen Listen standardmäßig privat',

    'archive_backups' => 'Wayback Machine Backups',
    'archive_backups_help' => 'Wenn aktiviert, teilt LinkAce dem <a href="https://archive.org/">Wayback Machine</a> mit, den Link zu sichern. Die Wayback Machine wird vom Internet Archive betrieben, einer gemeinnützigen Organisation. Bitte <a href="https://archive.org/donate/">spende an das Internet Archive</a>.',
    'archive_backups_enabled' => 'Sicherungen aktivieren',
    'archive_backups_enabled_help' => 'Wenn aktiviert, werden nicht-private Links vom Internet Archive gespeichert.',
    'archive_private_backups_enabled' => 'Sicherungen für private Links aktivieren',
    'archive_private_backups_enabled_help' => 'Wenn aktiviert, werden auch private Links gespeichert. Sicherungen müssen aktiviert sein.',

    'display_mode' => 'Links anzeigen als',
    'display_mode_list_detailed' => 'Liste mit vielen Details',
    'display_mode_list_simple' => 'Liste mit wenigen Details',
    'display_mode_cards' => 'Karten mit weniger Details',

    'sharing' => 'Link Sharing',
    'sharing_help' => 'Aktivieren Sie alle Dienste, die Sie für Links anzeigen möchten, um sie mit einem Klick einfach teilen zu können.',
    'sharing_toggle' => 'Alle de-/aktivieren',

    'darkmode' => 'Dark Mode',
    'darkmode_help' => 'Kann entweder permanent oder basierend auf den Geräteeinstellungen aktiviert werden. (<small>Überprüfen Sie <a href="https://caniuse.com/#search=prefers-color-scheme">hier</a>, ob Ihr Browser die automatische Erkennung unterstützt</small>)',
    'darkmode_disabled' => 'Deaktiviert',
    'darkmode_auto' => 'Automatisch',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Einstellungen speichern',
    'settings_saved' => 'Einstellungen erfolgreich aktualisiert!',

    'bookmarklet' => 'Bookmarklet',
    'bookmarklet_button' => 'Ziehen Sie dies zu Ihren Lesezeichen oder klicken Sie mit der rechten Maustaste und speichern Sie es als Lesezeichen',
    'bookmarklet_help' => 'Fügen Sie dieses Bookmarklet Ihrem Browser hinzu, um schnell Links von den besuchten Seiten hinzuzufügen, ohne LinkAce manuell öffnen zu müssen.',

    'change_password' => 'Passwort ändern',
    'old_password' => 'Altes Passwort',
    'new_password' => 'Neues Passwort',
    'new_password2' => 'Neues Passwort wiederholen',
    'password_updated' => 'Passwort erfolgreich geändert!',
    'old_password_invalid' => 'Das alte Passwort ist ungültig!',

    'two_factor_auth' => 'Zwei-Faktor-Authentifizierung',
    'two_factor_enable' => 'Zwei-Faktor-Authentifizierung aktivieren',
    'two_factor_disable' => 'Zwei-Faktor-Authentifizierung deaktivieren',
    'two_factor_setup_app' => 'Zwei-Faktor-Authentifizierung aktiviert. Bitte konfiguriere jetzt das Authentifizierungsgerät, indem der folgende QR-Code gescannt wird.',
    'two_factor_setup_url' => 'QR-Code funktioniert nicht? Alternativ kann auch die folgende URL verwendet werden.',
    'two_factor_recovery_codes' => 'Speicher diese Wiederherstellungscodes in einem sicheren Passwort-Manager. Sie können verwendet werden, um den Zugriff zum Konto wiederherzustellen, wenn das Zwei-Faktor-Authentifizierungsgerät verloren geht.',
    'two_factor_recovery_codes_view' => 'Wiederherstellungscodes anzeigen',
    'two_factor_regenerate_recovery_codes' => 'Neue Wiederherstellungscodes generieren',

    'api_token' => 'API Token',
    'api_token_generate' => 'Token generieren',
    'api_token_generate_confirm' => 'Wollen Sie wirklich einen neuen Token erstellen?',
    'api_token_help' => 'Der API-Token kann verwendet werden, um auf LinkAce von anderen Anwendungen oder Skripten aus zuzugreifen.',
    'api_token_generate_info' => 'Achtung: Wenn Sie bereits über ein API-Token verfügen, wird die Generierung eines neuen API-Token alle bestehenden Integrationen unterbrechen!',
    'api_token_generate_failure' => 'Ein neuer API-Token konnte nicht generiert werden. Bitte überprüfen Sie Ihre Browserkonsole und Anwendungsprotokolle für weitere Informationen.',

    'sys_page_title' => 'Seitentitel',
    'sys_guest_access' => 'Gastzugang aktivieren',
    'sys_guest_access_help' => 'Wenn aktiviert, können Gäste alle Links sehen, die nicht privat sind.',

    'cron_token' => 'Cron-Token',
    'cron_token_generate' => 'Token generieren',
    'cron_token_generate_confirm' => 'Wollen Sie wirklich einen neuen Token erstellen?',
    'cron_token_help' => 'Der cron-Token wird benötigt, um den cron Dienst auszuführen, der Links überprüft und Sicherungen durchführt.',
    'cron_token_url' => 'Zeigen Sie Ihren Cron auf die folgende URL: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Achtung: Wenn Sie bereits einen Cron-Token haben, wird der aktuell eingerichtete Cron Job unterbrochen!',
    'cron_token_generate_failure' => 'Ein neuer Cron-Token konnte nicht generiert werden. Bitte überprüfen Sie Ihre Browser-Konsole und Anwendungsprotokolle für weitere Informationen.',
    'cron_token_auth_failure' => 'Der angegebene Cron-Token ist ungültig',
    'cron_execute_successful' => 'Cron erfolgreich ausgeführt',

    'update_check' => 'Updateprüfung',
    'update_check_running' => 'Suche nach Updates...',
    'update_check_version_found' => 'Update gefunden. Version #VERSION# ist verfügbar.',
    'update_check_success' => 'Kein Update gefunden.',
    'update_check_failed' => 'Konnte nicht nach Updates suchen.',

    'guest_settings_info' => 'Die folgenden Einstellungen gelten für alle Gäste, die die Webseite besuchen, sofern der Gastzugang aktiviert ist.',
];
