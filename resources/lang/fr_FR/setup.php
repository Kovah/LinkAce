<?php

return [
    'setup' => 'Configuration',
    'continue' => 'Continuer',
    'try_again' => 'Réessayer',

    'welcome' => 'Bienvenue dans la configuration de LinkAce',
    'intro' => 'Dans les étapes suivantes vous allez configurer LinkAce pour qu\'il soit prêt à l\'emploi.',
    'intro.step1' => 'Vérifier si toutes les prérequis sont remplis.',
    'intro.step2' => 'Configurez une base de données et vérifiez si la connexion est réussie.',
    'intro.step3' => 'Créez votre compte utilisateur.',

    'check_requirements' => 'Vérifier Prérequis',
    'requirements.php_version' => 'Version PHP >= 7.2.0',
    'requirements.extension_bcmath' => 'Module PHP : BCMath',
    'requirements.extension_ctype' => 'Module PHP : Ctype',
    'requirements.extension_json' => 'Module PHP : JSON',
    'requirements.extension_mbstring' => 'Module PHP : Mbstring',
    'requirements.extension_openssl' => 'Module PHP : OpenSSL',
    'requirements.extension_pdo_mysql' => 'Module PHP : PDO',
    'requirements.extension_tokenizer' => 'Module PHP : Tokenizer',
    'requirements.extension_xml' => 'Module PHP : XML',
    'requirements.env_writable' => 'Le fichier .env est présent et accessible en écriture',
    'requirements.storage_writable' => 'Les répertoires /storage et /storage/logs sont accessibles en écriture',

    'database_configuration' => 'Configuration Base de données',
    'database_configure' => 'Configurer Base de données',
    'database.intro' => 'Si vous avez déjà rempli les détails de la base de données dans votre fichier .env, les champs de saisie devraient être pré-remplis. Sinon, remplissez les champs avec les informations correspondantes à votre base de données.',
    'database.config_error' => 'La base de données n\'a pas pu être configurée. Veuillez vérifier vos informations de connexion. Détails :',
    'database.db_host' => 'Hôte Base de données',
    'database.db_port' => 'Port Base de données',
    'database.db_name' => 'Nom Base de données',
    'database.db_user' => 'Utilisateur Base de données',
    'database.db_password' => 'Mot de passe Base de données',
    'database.complete_hint' => 'L\'enregistrement de la configuration de la base de données et sa préparation pour l\'application peuvent prendre quelques secondes, merci de patienter.',

    'database.data_present' => 'Attention ! Nous avons trouvé des données dans la base de données que vous avez spécifiée ! Veuillez vous assurer que vous avez une sauvegarde de cette base de données et confirmez la suppression de toutes les données.',
    'database.overwrite_data' => 'Je confirme que toutes les données doivent être supprimées et remplacées par une nouvelle base de données LinkAce',

    'account_setup' => 'Configuration Compte',
    'account_setup.intro' => 'Avant de commencer, vous devez créer votre compte utilisateur.',
    'account_setup.name' => 'Entrez votre nom',
    'account_setup.email' => 'Entrez votre adresse e-mail',
    'account_setup.password' => 'Entrez un mot de passe fort',
    'account_setup.password_requirements' => 'Longueur minimale : 10 caractères',
    'account_setup.password_confirmed' => 'Confirmez votre mot de passe',
    'account_setup.create' => 'Créer un compte',

    'complete' => 'Configuration terminée !',
    'outro' => 'Vous avez terminé la configuration et pouvez maintenant utiliser LinkAce ! Vous êtes connecté et pouvez commencer à ajouter vos signets immédiatement.',
];
