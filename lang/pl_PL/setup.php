<?php

return [
    'setup' => 'Konfiguracja',
    'continue' => 'Kontynuuj',
    'try_again' => 'Spróbuj ponownie',

    'welcome' => 'Witamy w konfiguracji LinkAce',
    'intro' => 'W następujących krokach skonfigurujesz LinkAce do użytkowania.',
    'intro.step1' => 'Sprawdź, czy wszystkie wymagania są spełnione.',
    'intro.step2' => 'Skonfiguruj bazę danych i sprawdź, czy połączenie zakończyło się pomyślnie.',
    'intro.step3' => 'Utwórz swoje konto użytkownika.',

    'check_requirements' => 'Sprawdź wymagania',
    'requirements.php_version' => 'Wersja PHP >= 7.4.0',
    'requirements.extension_bcmath' => 'Rozszerzenie PHP: BCMath',
    'requirements.extension_ctype' => 'Rozszerzenie PHP: Ctype',
    'requirements.extension_json' => 'Rozszerzenie PHP: JSON',
    'requirements.extension_mbstring' => 'Rozszerzenie PHP: Mbstring',
    'requirements.extension_openssl' => 'Rozszerzenie PHP: OpenSSL',
    'requirements.extension_pdo_mysql' => 'Rozszerzenie PHP: PDO MySQL',
    'requirements.extension_tokenizer' => 'Rozszerzenie PHP: Tokenizer',
    'requirements.extension_xml' => 'Rozszerzenie PHP: XML',
    'requirements.env_writable' => 'Plik .env jest obecny i zapisywalny',
    'requirements.storage_writable' => 'katalogi /storage i /storage/logs są zapisywalne',

    'database_configuration' => 'Konfiguracja bazy danych',
    'database_configure' => 'Konfiguruj bazę danych',
    'database.intro' => 'Jeśli już wypełniłeś szczegóły bazy danych w pliku .env, pola wejściowe powinny być wstępnie wypełnione. W przeciwnym razie wypełnij pola odpowiednimi informacjami dla Twojej bazy danych.',
    'database.config_error' => 'Baza danych nie może być skonfigurowana. Sprawdź szczegóły połączenia. Szczegóły:',
    'database.db_host' => 'Host bazy danych',
    'database.db_port' => 'Port bazy danych',
    'database.db_name' => 'Nazwa bazy danych',
    'database.db_user' => 'Użytkownik bazy danych',
    'database.db_password' => 'Hasło bazy danych',
    'database.complete_hint' => 'Zapisywanie konfiguracji bazy danych i przygotowanie jej do korzystania z aplikacji może potrwać kilka sekund, prosimy o cierpliwość.',

    'database.data_present' => 'Uwaga! Znaleźliśmy dane w bazie danych którą podałeś! Upewnij się, że masz kopię zapasową tej bazy danych i potwierdź usunięcie wszystkich danych.',
    'database.overwrite_data' => 'Potwierdzam, że wszystkie dane powinny zostać usunięte i nadpisane nową bazą danych LinkAce',

    'account_setup' => 'Konfiguracja konta',
    'account_setup.intro' => 'Zanim zaczniesz musisz utworzyć swoje konto użytkownika.',
    'account_setup.name' => 'Wprowadź swoje imię',
    'account_setup.email' => 'Wprowadź swój adres email',
    'account_setup.password' => 'Wprowadź silne hasło',
    'account_setup.password_requirements' => 'Minimalna długość: 10 znaków',
    'account_setup.password_confirmed' => 'Potwierdź swoje hasło',
    'account_setup.create' => 'Utwórz konto',

    'complete' => 'Konfiguracja zakończona!',
    'outro' => 'Ukończyłeś konfigurację i możesz teraz korzystać z LinkAce! Jesteś zalogowany i możesz rozpocząć tworzenie zakładek od razu.',
];
