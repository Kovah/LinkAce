<?php

return [
    'setup' => 'Installasjon',
    'continue' => 'Fortsett',
    'try_again' => 'Prøv på nytt',

    'welcome' => 'Velkommen til installasjon av LinkAce',
    'intro' => 'I de følgende stegene så vil du sette opp LinkAce til å være klar for bruk.',
    'intro.step1' => 'Sjekk om alle kravene er oppfylt.',
    'intro.step2' => 'Opprett en database og kontroller om tilkoblingen er vellykket.',
    'intro.step3' => 'Opprett din brukerkonto.',

    'check_requirements' => 'Kontroller kravene',
    'requirements.php_version' => 'PHP versjon >= 7.4.0',
    'requirements.extension_bcmath' => 'PHP utvidelse: BCMath',
    'requirements.extension_ctype' => 'PHP utvidelse: Ctype',
    'requirements.extension_json' => 'PHP utvidelse: JSON',
    'requirements.extension_mbstring' => 'PHP utvidelse: Mbstring',
    'requirements.extension_openssl' => 'PHP utvidelse: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP utvidelse: PDO MySQL',
    'requirements.extension_tokenizer' => 'PHP utvidelse: Tokenizer',
    'requirements.extension_xml' => 'PHP utvidelse: XML',
    'requirements.env_writable' => '.env filen finnes og er skrivbar',
    'requirements.storage_writable' => '/storage og /storage/logs mappene er skrivbare',

    'database_configuration' => 'Databasekonfigurasjon',
    'database_configure' => 'Konfigurer databasen',
    'database.intro' => 'Dersom du allerede har fylt ut databasedetaljene i .env filen, så bør inntastingsfeltene være forhåndsutfylt. Ellers fyll ut feltene med nødvendig informasjon til databasen.',
    'database.config_error' => 'Databasen kunne ikke konfigureres. Kontroller tilkoblingsdetaljene. Detaljer:',
    'database.db_host' => 'Database vert',
    'database.db_port' => 'Database port',
    'database.db_name' => 'Database navn',
    'database.db_user' => 'Database bruker',
    'database.db_password' => 'Database passord',
    'database.complete_hint' => 'Lagrer databasekonfigurasjonen og klargjør den for bruk av applikasjonen kan ta noen sekunder, vennligst vær tålmodig.',

    'database.data_present' => 'Forsiktig! Vi fant data i databasen du har angitt! Sørg for at du har en sikkerhetskopi av databasen og bekreft sletting av alle data.',
    'database.overwrite_data' => 'Jeg bekrefter at alle dataene skal slettes og overskrives med en ny LinkAce database',

    'account_setup' => 'Konfigurering av konto',
    'account_setup.intro' => 'Før du kan starte må du opprette brukerkontoen din.',
    'account_setup.name' => 'Oppgi ditt navn',
    'account_setup.email' => 'Oppgi din e-postadresse',
    'account_setup.password' => 'Angi et sterkt passord',
    'account_setup.password_requirements' => 'Minimumslengde: 10 tegn',
    'account_setup.password_confirmed' => 'Bekreft passordet',
    'account_setup.create' => 'Opprett konto',

    'complete' => 'Installasjon fullført!',
    'outro' => 'Du har fullført installasjonen og kan nå bruke LinkAce! Du er logget inn og kan begynne å legge til bokmerker med en gang.',
];
