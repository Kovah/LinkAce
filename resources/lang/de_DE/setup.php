<?php

return [
    'setup' => 'Setup',
    'continue' => 'Weiter',
    'try_again' => 'Erneut versuchen',

    'welcome' => 'Willkommen zum LinkAce Setup',
    'intro' => 'In den folgenden Schritten werden Sie LinkAce so einrichten, dass Sie es verwenden können.',
    'intro.step1' => 'Überprüfen Sie, ob alle Anforderungen erfüllt sind.',
    'intro.step2' => 'Richten Sie eine Datenbank ein und prüfen Sie, ob die Verbindung erfolgreich ist.',
    'intro.step3' => 'Erstellen Sie Ihr Benutzerkonto.',

    'check_requirements' => 'Anforderungen prüfen',
    'requirements.php_version' => 'PHP Version >= 7.2.0',
    'requirements.extension_bcmath' => 'PHP Extension: BCMath',
    'requirements.extension_ctype' => 'PHP Extension: Ctype',
    'requirements.extension_json' => 'PHP Extension: JSON',
    'requirements.extension_mbstring' => 'PHP Extension: Mbstring',
    'requirements.extension_openssl' => 'PHP Extension: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP Extension: PDO',
    'requirements.extension_tokenizer' => 'PHP Extension: Tokenizer',
    'requirements.extension_xml' => 'PHP Extension: XML',
    'requirements.env_writable' => '.env Datei ist vorhanden und beschreibbar',
    'requirements.storage_writable' => '/storage und /storage/logs Verzeichnisse sind beschreibbar',

    'database_configuration' => 'Datenbankkonfiguration',
    'database_configure' => 'Datenbank konfigurieren',
    'database.intro' => 'Wenn Sie bereits die Datenbankdaten in Ihrer .env-Datei konfiguriert haben, sollten die Eingabefelder vorausgefüllt sein. Andernfalls füllen Sie die Felder mit den entsprechenden Informationen für Ihre Datenbank.',
    'database.config_error' => 'Datenbank konnte nicht konfiguriert werden. Bitte überprüfen Sie Ihre Verbindungsdetails. Details:',
    'database.db_host' => 'Datenbank-Host',
    'database.db_port' => 'Datenbank-Port',
    'database.db_name' => 'Name der Datenbank',
    'database.db_user' => 'Datenbank-Benutzer',
    'database.db_password' => 'Datenbank-Passwort',
    'database.complete_hint' => 'Das Speichern der Datenbankkonfiguration und die Vorbereitung für die Verwendung der App kann ein paar Sekunden dauern, bitte haben Sie Geduld.',

    'database.data_present' => 'Achtung! Wir haben Daten in der von Ihnen angegebenen Datenbank gefunden! Bitte stellen Sie sicher, dass Sie eine Sicherung dieser Datenbank haben und bestätigen Sie die Löschung aller Daten.',
    'database.overwrite_data' => 'Ich bestätige, dass alle Daten mit einer neuen LinkAce Datenbank gelöscht und überschrieben werden sollen',

    'account_setup' => 'Kontoeinrichtung',
    'account_setup.intro' => 'Bevor Sie beginnen können, müssen Sie Ihr Benutzerkonto anlegen.',
    'account_setup.name' => 'Bitte geben Sie Ihren Namen ein',
    'account_setup.email' => 'Geben Sie Ihre E-Mail-Adresse ein',
    'account_setup.password' => 'Ein starkes Passwort eingeben',
    'account_setup.password_requirements' => 'Mindestlänge: 10 Zeichen',
    'account_setup.password_confirmed' => 'Passwort bestätigen',
    'account_setup.create' => 'Konto erstellen',

    'complete' => 'Setup abgeschlossen!',
    'outro' => 'Sie haben das Setup abgeschlossen und können jetzt LinkAce benutzen! Sie sind eingeloggt und können sofort das Bookmarking starten.',
];
