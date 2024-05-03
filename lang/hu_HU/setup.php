<?php

return [
    'setup' => 'Telepítés',
    'continue' => 'Tovább',
    'try_again' => 'Próbálja újra',

    'welcome' => 'Üdvözli a LinkAce-telepítő',
    'intro' => 'A következő lépésekben beállítja a LinkAce-t, hogy készen álljon a használatra.',
    'intro.step1' => 'Ellenőrizze, hogy minden követelmény teljesül-e.',
    'intro.step2' => 'Állítson be egy adatbázist, és ellenőrizze, hogy a kapcsolat sikerült-e.',
    'intro.step3' => 'Hozzon létre felhasználói fiókot.',

    'check_requirements' => 'Ellenőrizze a követelményeket',
    'requirements.php_version' => 'PHP-verzió >= 7.4.0',
    'requirements.extension_bcmath' => 'PHP-bővítmény: BCMath',
    'requirements.extension_ctype' => 'PHP-bővítmény: Ctype',
    'requirements.extension_json' => 'PHP-bővítmény: JSON',
    'requirements.extension_mbstring' => 'PHP-bővítmény: Mbstring',
    'requirements.extension_openssl' => 'PHP-bővítmény: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP-bővítmény: PDO MySQL',
    'requirements.extension_tokenizer' => 'PHP-bővítmény: Tokenizer',
    'requirements.extension_xml' => 'PHP-bővítmény: XML',
    'requirements.env_writable' => '.env-fájl jelen van és írható',
    'requirements.storage_writable' => '/storage és /storage/logs könyvtárak írhatók',

    'database_configuration' => 'Adatbázis konfigurációja',
    'database_configure' => 'Adatbázis konfigurálása',
    'database.intro' => 'Ha már kitöltötte az adatbázis adatait az .env-fájlban, a beviteli mezőknek előre ki kell lenniük töltve. Ellenkező esetben töltse ki a mezőket az adatbázisának megfelelő adatokkal.',
    'database.config_error' => 'Az adatbázist nem lehetett konfigurálni. Kérjük, ellenőrizze a kapcsolat adatait. Részletek:',
    'database.db_host' => 'Adatbázisgazda',
    'database.db_port' => 'Adatbázisport',
    'database.db_name' => 'Adatbázis neve',
    'database.db_user' => 'Adatbázis-felhasználó',
    'database.db_password' => 'Jelszó az adatbázishoz',
    'database.complete_hint' => 'Az adatbázis-konfiguráció mentése és előkészítése az alkalmazás használatára néhány másodpercet vehet igénybe. Kérjük, legyen türelmes.',

    'database.data_present' => 'Figyelmeztetés! Adatokat találtunk az Ön által megadott adatbázisban! Kérjük, győződjön meg róla, hogy van biztonsági másolata az adatbázisról, és erősítse meg az összes adat törlését.',
    'database.overwrite_data' => 'Megerősítem, hogy minden adat törlendő és felülírandó egy új LinkAce-adatbázissal',

    'account_setup' => 'Fiók beállítása',
    'account_setup.intro' => 'Mielőtt elkezdené, létre kell hoznia a felhasználói fiókját.',
    'account_setup.name' => 'Adja meg a nevét',
    'account_setup.email' => 'Adja meg az e-mail-címét',
    'account_setup.password' => 'Adjon meg egy erős jelszót',
    'account_setup.password_requirements' => 'Minimális hossz: 8 karakter',
    'account_setup.password_confirmed' => 'Jelszó megerősítése',
    'account_setup.create' => 'Fiók létrehozása',

    'complete' => 'A telepítés befejeződött!',
    'outro' => 'Befejezte a telepítést, és most már használhatja LinkAce-t! Be van jelentkezve, és máris elkezdheti a könyvjelzők használatát.',
];
