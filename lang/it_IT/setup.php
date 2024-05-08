<?php

return [
    'setup' => 'Configurazione',
    'continue' => 'Continua',
    'try_again' => 'Riprova',

    'welcome' => 'Benvenuto nella configurazione di LinkAce',
    'intro' => 'Nei prossimi passaggi configurerai LinkAce affinché sia pronto all\'uso.',
    'intro.step1' => 'Controlla se tutti i requisiti siano soddisfatti.',
    'intro.step2' => 'Configura un database e controlla se la connessione è riuscita.',
    'intro.step3' => 'Crea il tuo account utente.',

    'check_requirements' => 'Verifica Requisiti',
    'requirements.php_version' => 'Versione di PHP >= 7.4.0',
    'requirements.extension_bcmath' => 'Estensione PHP: BCMath',
    'requirements.extension_ctype' => 'Estensione PHP: Ctype',
    'requirements.extension_json' => 'Estensione PHP: JSON',
    'requirements.extension_mbstring' => 'Estensione PHP: Mbstring',
    'requirements.extension_openssl' => 'Estensione PHP: OpenSSL',
    'requirements.extension_pdo_mysql' => 'Estensione PHP: PDO MySQL',
    'requirements.extension_tokenizer' => 'Estensione PHP: Tokenizer',
    'requirements.extension_xml' => 'Estensione PHP: XML',
    'requirements.env_writable' => 'Il file .env esiste ed è modificabile',
    'requirements.storage_writable' => 'le directory /storage e /storage/log sono modificabili',

    'database_configuration' => 'Configurazione Database',
    'database_configure' => 'Configura Database',
    'database.intro' => 'Se hai già compilato i dettagli del database nel tuo file .env i campi di input dovrebbero essere precompilati. Altrimenti, compila i campi con le informazioni corrispondenti al tuo database.',
    'database.config_error' => 'Il database non può essere configurato. Controlla i dettagli della tua connessione. Dettagli:',
    'database.db_host' => 'Hostname del Database Server',
    'database.db_port' => 'Porta del Database Server',
    'database.db_name' => 'Nome del Database',
    'database.db_user' => 'Utente del Database',
    'database.db_password' => 'Password Utente del Database',
    'database.complete_hint' => 'Salvare la configurazione del database e prepararla per l\'utilizzo dell\'app potrebbe richiedere alcuni secondi, si prega di essere pazienti.',

    'database.data_present' => 'Attenzione! Abbiamo trovato dati nel database che hai specificato! Assicurati di avere un backup di quel database e conferma la cancellazione di tutti i dati.',
    'database.overwrite_data' => 'Confermo che tutti i dati saranno cancellati e sovrascritti con un nuovo database LinkAce',

    'account_setup' => 'Configurazione Account',
    'account_setup.intro' => 'Prima di poter iniziare devi creare il tuo account utente.',
    'account_setup.name' => 'Inserisci il tuo nome',
    'account_setup.email' => 'Inserisci il tuo indirizzo email',
    'account_setup.password' => 'Inserisci una password robusta',
    'account_setup.password_requirements' => 'Lunghezza minima: 10 caratteri',
    'account_setup.password_confirmed' => 'Conferma la tua password',
    'account_setup.create' => 'Crea account',

    'complete' => 'Configurazione completata!',
    'outro' => 'Hai completato la configurazione e ora puoi usare LinkAce! Hai effettuato l\'accesso e puoi iniziare subito.',
];
