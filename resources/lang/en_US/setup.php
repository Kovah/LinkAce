<?php

return [
    'setup' => 'Setup',
    'continue' => 'Continue',
    'try_again' => 'Try again',

    'welcome' => 'Welcome to the LinkAce setup',
    'intro' => 'In the following steps you will set up LinkAce to be ready to use.',
    'intro.step1' => 'Check if all requirements are met.',
    'intro.step2' => 'Setup up a database and check if the connection is successful.',
    'intro.step3' => 'Create your user account.',

    'check_requirements' => 'Check Requirements',
    'requirements.php_version' => 'PHP version >= 7.3.0',
    'requirements.extension_bcmath' => 'PHP Extension: BCMath',
    'requirements.extension_ctype' => 'PHP Extension: Ctype',
    'requirements.extension_json' => 'PHP Extension: JSON',
    'requirements.extension_mbstring' => 'PHP Extension: Mbstring',
    'requirements.extension_openssl' => 'PHP Extension: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP Extension: PDO',
    'requirements.extension_tokenizer' => 'PHP Extension: Tokenizer',
    'requirements.extension_xml' => 'PHP Extension: XML',
    'requirements.env_writable' => '.env file is present and writable',
    'requirements.storage_writable' => '/storage and /storage/logs directories are writable',

    'database_configuration' => 'Database Configuration',
    'database_configure' => 'Configure Database',
    'database.intro' => 'If you already filled the database details in your .env file the input fields should be pre-filled. Otherwise, fill the fields with the corresponding information for your database.',
    'database.config_error' => 'Database could not be configured. Please check your connection details. Details:',
    'database.db_host' => 'Database Host',
    'database.db_port' => 'Database Port',
    'database.db_name' => 'Database Name',
    'database.db_user' => 'Database User',
    'database.db_password' => 'Database Password',
    'database.complete_hint' => 'Saving the database configuration and preparing it for using the app may take a few seconds, please be patient.',

    'database.data_present' => 'Caution! We found data in the database you specified! Please make sure that you have a backup of that database and confirm the deletion of all data.',
    'database.overwrite_data' => 'I confirm that all data should be deleted and overwritten with a new LinkAce database',

    'account_setup' => 'Account Setup',
    'account_setup.intro' => 'Before you can start you have to create your user account.',
    'account_setup.name' => 'Enter your name',
    'account_setup.email' => 'Enter your email address',
    'account_setup.password' => 'Enter a strong password',
    'account_setup.password_requirements' => 'Minimum length: 10 characters',
    'account_setup.password_confirmed' => 'Confirm your password',
    'account_setup.create' => 'Create account',

    'complete' => 'Setup completed!',
    'outro' => 'You completed the setup and can now use LinkAce! You are logged in and can start bookmarking right away.',
];
