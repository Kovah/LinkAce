<?php

return [
    'setup' => 'Configurare',
    'continue' => 'Continuare',
    'try_again' => 'Încearcă din nou',

    'welcome' => 'Bun venit la configurarea LinkAce',
    'intro' => 'În următorii pași vei configura LinkAce pentru a fi gata de utilizare.',
    'intro.step1' => 'Verifică dacă sunt îndeplinite toate cerințele.',
    'intro.step2' => 'Configurează o bază de date și verifică dacă se realizează conexiunea la aceasta.',
    'intro.step3' => 'Creează-ți contul de utilizator.',

    'setup_requirements' => 'Cerințe de configurare',
    'check_requirements' => 'Verificare cerințe',
    'requirements.php_version' => 'Versiune PHP >= 7.4.0',
    'requirements.extension_bcmath' => 'Extensie PHP: BCMath',
    'requirements.extension_ctype' => 'Extensie PHP: Ctype',
    'requirements.extension_json' => 'Extensie PHP: JSON',
    'requirements.extension_mbstring' => 'Extensie PHP: Mbstring',
    'requirements.extension_openssl' => 'Extensie PHP: OpenSSL',
    'requirements.extension_pdo_mysql' => 'Extensie PHP: PDO MySQL',
    'requirements.extension_tokenizer' => 'Extensie PHP: Tokenizer',
    'requirements.extension_xml' => 'Extensie PHP: XML',
    'requirements.env_writable' => 'Fișierul .env există și este inscripționabil',
    'requirements.storage_writable' => 'Directoarele /storage și /storage/logs sunt inscripționabile',

    'database_configuration' => 'Configurarea bazei de date',
    'database_configure' => 'Configurare bază de date',
    'database.intro' => 'Dacă ai completat deja detaliile bazei de date în fișierul .env, atunci câmpurile de introducere ar trebui să fie completate în prealabil. În caz contrar, completează câmpurile cu informațiile corespunzătoare pentru baza ta de date.',
    'database.config_error' => 'Baza de date nu a putut fi configurată. Verifică detaliile conexiunii tale. Detalii:',
    'database.db_host' => 'Gazda bazei de date',
    'database.db_port' => 'Portul bazei de date',
    'database.db_name' => 'Denumirea bazei de date',
    'database.db_user' => 'Utilizatorul bazei de date',
    'database.db_password' => 'Parola bazei de date',
    'database.complete_hint' => 'Salvarea configurației bazei de date și pregătirea acesteia în vederea utilizării aplicației poate dura câteva secunde. Te rugăm să ai răbdare.',

    'database.data_present' => 'Atenție! Am descoperit date în baza de date specificată! Asigură-te că ai o copie de rezervă pentru această bază de date și confirmă ștergerea tuturor datelor.',
    'database.overwrite_data' => 'Confirm că toate datele trebuie șterse și suprascrise cu o nouă bază de date LinkAce',

    'account_setup' => 'Configurare cont',
    'account_setup.intro' => 'Înainte de a putea începe, trebuie să îți creezi contul de utilizator.',
    'account_setup.name' => 'Introdu-ți numele',
    'account_setup.email' => 'Introdu-ți adresa de poștă electronică',
    'account_setup.password' => 'Introdu o parolă puternică',
    'account_setup.password_requirements' => 'Lungime minimă: 10 caractere',
    'account_setup.password_confirmed' => 'Confirmă-ți parola',
    'account_setup.create' => 'Creare cont',

    'complete' => 'Configurare finalizată!',
    'outro' => 'Ai finalizat configurarea și acum poți utiliza LinkAce! Te-ai autentificat și poți începe să aplici marcaje imediat.',
];
