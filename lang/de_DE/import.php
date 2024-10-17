<?php
return [
    'import' => 'Import',
    'import_queue' => 'Import-Warteschlange',
    'failed_imports' => 'Fehlgeschlagene Imports',
    'scheduled_for' => 'Geplant für',
    'start_import' => 'Importieren starten',
    'import_running' => 'Import läuft...',
    'import_file' => 'Datei für den Import',

    'import_help' => 'Sie können hier Ihre vorhandenen Lesezeichen importieren. Normalerweise werden Lesezeichen von Ihrem Browser in eine .html Datei exportiert. Wählen Sie die Datei hier aus und starten Sie den Import. Bitte beachten Sie, dass ein Cron konfiguriert sein muss, damit der Import funktioniert.',

    'import_networkerror' => 'Beim Importieren der Lesezeichen ist ein Fehler aufgetreten. Bitte überprüfen Sie die Konsole des Browsers oder die Logs der Anwendung für Details.',
    'import_error' => 'Beim Importieren der Lesezeichen ist ein Fehler aufgetreten. Bitte prüfen Sie die Logs der Anwendung.',
    'import_empty' => 'Konnte keine Lesezeichen importieren. Entweder ist die hochgeladene Datei beschädigt oder leer.',
    'import_successfully' => 'Für :queued Links wurde der Import geplant, sie werden nacheinander verarbeitet. :skipped Links wurden übersprungen, weil sie bereits in der Datenbank existieren. Alle importierten Links werden dem Tag :taglink zugeordnet.',
];
