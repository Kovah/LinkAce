<?php

return [
    'setup' => 'Configuració',
    'continue' => 'Continua',
    'try_again' => 'Torna-ho a provar',

    'welcome' => 'Ben-vingut a la configuració de LinkAce',
    'intro' => 'A les següentes passes configurarà LinkAce per que estigui llest per fer servir.',
    'intro.step1' => 'Comprova si es compleixen tots els requeriments.',
    'intro.step2' => 'Configurar una base de dades i comprovar si la connexió s\'ha realitzat correctament.',
    'intro.step3' => 'Crea compte d\'usuari nou.',

    'check_requirements' => 'Comprovar requeriments',
    'requirements.php_version' => 'Versió de PHP >= 7.4.0',
    'requirements.extension_bcmath' => 'Extensió PHP: BCMath',
    'requirements.extension_ctype' => 'Extensió PHP: Ctype',
    'requirements.extension_json' => 'Extensió PHP: JSON',
    'requirements.extension_mbstring' => 'Extensió PHP: Mbstring',
    'requirements.extension_openssl' => 'Extensió PHP: OpenSSL',
    'requirements.extension_pdo_mysql' => 'Extensió PHP: PDO MySQL',
    'requirements.extension_tokenizer' => 'Extensió PHP: Tokenizer',
    'requirements.extension_xml' => 'Extensió PHP: XML',
    'requirements.env_writable' => 'L\'arxiu .env està present i té permisos d\'escriptura',
    'requirements.storage_writable' => 'Els directoris /storage i /storage/logs tenen permisos d\'escriptura',

    'database_configuration' => 'Configuració de la Base de Dades',
    'database_configure' => 'Configurar base de dades',
    'database.intro' => 'Si has omplert els detalls de la base de dades evo .env els camps d\'entrada s\'haurien de pre-omplir. Si no, ompli els camps amb l\'informació corresponent per la teva base de dades.',
    'database.config_error' => 'La base de dades no s\'ha pogut configurar. Si us plau, comprova els detalls de la connexió. Detalls:',
    'database.db_host' => 'Host de la base de dades',
    'database.db_port' => 'Port de base de dades',
    'database.db_name' => 'Nom de base de dades',
    'database.db_user' => 'Usuari de base de dades',
    'database.db_password' => 'Contrasenya de la base de dades',
    'database.complete_hint' => 'Guardar la configuració de la base de dades i preparar-la per utilitzar-la per l\'aplicació pot trigar uns segons, si us plau siguis pacient.',

    'database.data_present' => 'Precaució! S\'han trobat dades en la base de dades especificada! Si us plau, asegurat de que tens una còpia de seguretat d\'aquesta base de dades i confirma l\'eliminació de totes les dades.',
    'database.overwrite_data' => 'Confirmo que totes les dades han de ser esborrades i sobre-escrits amb una nova base de dades de LinkAce',

    'account_setup' => 'Configuración del compte',
    'account_setup.intro' => 'Abans de començar has de crear el teu compte d\'usuari.',
    'account_setup.name' => 'Introdueix el teu nom',
    'account_setup.email' => 'Introdueix el teu correu electrònic',
    'account_setup.password' => 'Introdueix una contrasenya forta',
    'account_setup.password_requirements' => 'Longitud mínima: 10 caràcters',
    'account_setup.password_confirmed' => 'Confirma la teva contrasenya',
    'account_setup.create' => 'Crear compte',

    'complete' => '¡Configuració completada!',
    'outro' => 'Has finalitzat la configuració i ara pots fer servir LinkAce! Has iniciat sessió i pots iniciar-te al bookmarking de forma inmediata.',
];
