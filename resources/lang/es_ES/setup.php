<?php

return [
    'setup' => 'Configuración',
    'continue' => 'Continuar',
    'try_again' => 'Intentar de nuevo',

    'welcome' => 'Bienvenido a la configuración de LinkAce',
    'intro' => 'En los siguientes pasos configurará LinkAce para que esté listo para usar.',
    'intro.step1' => 'Compruebe si se cumplen todos los requisitos.',
    'intro.step2' => 'Configurar una base de datos y comprobar si la conexión se ha realizado correctamente.',
    'intro.step3' => 'Crea tu cuenta de usuario.',

    'check_requirements' => 'Comprobar requisitos',
    'requirements.php_version' => 'Versión PHP >= 7.3.0',
    'requirements.extension_bcmath' => 'Extensión PHP: BCMath',
    'requirements.extension_ctype' => 'Extensión PHP: Ctype',
    'requirements.extension_json' => 'Extensión PHP: JSON',
    'requirements.extension_mbstring' => 'Extensión PHP: Mbstring',
    'requirements.extension_openssl' => 'Extensión PHP: OpenSSL',
    'requirements.extension_pdo_mysql' => 'Extensión PHP: PDO',
    'requirements.extension_tokenizer' => 'Extensión PHP: Tokenizer',
    'requirements.extension_xml' => 'Extensión PHP: XML',
    'requirements.env_writable' => 'El archivo .env está presente y tiene permisos de escritura',
    'requirements.storage_writable' => 'Los directorios /storage y /storage/logs tienen permisos de escritura',

    'database_configuration' => 'Configuración de la base de datos',
    'database_configure' => 'Configurar base de datos',
    'database.intro' => 'Si ya ha rellenado los detalles de la base de datos en su archivo .env los campos de entrada deben ser pre-llenados. De lo contrario, rellene los campos con la información correspondiente para su base de datos.',
    'database.config_error' => 'La base de datos no pudo ser configurada. Por favor, compruebe los detalles de la conexión. Detalles:',
    'database.db_host' => 'Host de base de datos',
    'database.db_port' => 'Puerto de base de datos',
    'database.db_name' => 'Nombre de base de datos',
    'database.db_user' => 'Usuario de base de datos',
    'database.db_password' => 'Contraseña de la base de datos',
    'database.complete_hint' => 'Guardar la configuración de la base de datos y prepararla para usar la aplicación puede tardar unos segundos, por favor sea paciente.',

    'database.data_present' => '¡Precaución! ¡Encontramos datos en la base de datos especificada! Por favor, asegúrese de que tiene una copia de seguridad de esa base de datos y confirme la eliminación de todos los datos.',
    'database.overwrite_data' => 'Confirmo que todos los datos deben ser borrados y sobrescritos con una nueva base de datos de LinkAce',

    'account_setup' => 'Configuración de cuenta',
    'account_setup.intro' => 'Antes de empezar tienes que crear tu cuenta de usuario.',
    'account_setup.name' => 'Introduzca su nombre',
    'account_setup.email' => 'Introduzca su dirección de correo',
    'account_setup.password' => 'Introduzca una contraseña segura',
    'account_setup.password_requirements' => 'Longitud mínima: 10 caracteres',
    'account_setup.password_confirmed' => 'Confirme su contraseña',
    'account_setup.create' => 'Crear cuenta',

    'complete' => '¡Configuración completada!',
    'outro' => 'Ha completado la configuración y ahora puede utilizar LinkAce! Ha iniciado sesión y puede iniciarse en el bookmarking de forma inmediata.',
];
