<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'register' => 'S\'inscrire',
    'register_welcome' => 'Bienvenue sur LinkAce ! Vous avez été invité à rejoindre cet outil de marque-pages collaboratif. Merci de sélectionner un nom d\'utilisateur et un mot de passe. Une fois l\'inscription réussie, vous serez redirigé vers le tableau de bord.',

    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    'confirm_title' => 'Confirmation requise',
    'confirm' => 'Veuillez confirmer cette action en utilisant votre mot de passe actuel.',
    'confirm_action' => 'Confirmer l\'action',

    'two_factor' => 'Authentification à double facteur',
    'two_factor_check' => 'Veuillez entrer le mot de passe unique fourni par votre application d\'authentification à double facteur maintenant.',
    'two_factor_with_recovery' => 'Authentification avec le code de récupération',

    'api_tokens' => 'Jetons API',
    'api_tokens.no_tokens_found' => 'Aucun Jeton API trouvé.',
    'api_tokens.generate' => 'Générer un nouveau jeton API',
    'api_tokens.generate_short' => 'Générer un jeton',
    'api_tokens.generate_help' => 'Les Jetons API sont utilisés pour vous authentifier auprès de l\'API LinkAce.',
    'api_tokens.generated_successfully' => 'Le Jeton API a été généré avec succès : <code>:token</code>',
    'api_tokens.generated_help' => 'Veuillez stocker ce Jeton dans un endroit sûr. Il n\'est <strong>pas</strong> possible de récupérer votre Jeton s\'il est perdu.',
    'api_tokens.name' => 'Nom du jeton',
    'api_tokens.name_help' => 'Choisissez un nom pour votre Jeton. Le nom ne peut contenir que des caractères alphanumériques, des tirets et des tirets bas. Utile si vous voulez créer des Jetons distincts pour différents cas d\'utilisation ou applications.',

    'api_token_system' => 'Jeton API système',
    'api_tokens_system' => 'Jetons API système',
    'api_tokens.generate_help_system' => 'Les Jetons API sont utilisés pour accéder à l\'API LinkAce à partir d\'autres applications ou de scripts. Par défaut, seules les données publiques ou internes sont accessibles, mais il est possible d\'accorder aux Jetons un accès supplémentaire aux données privées si nécessaire.',
    'api_tokens.private_access' => 'Le Jeton peut accéder à des données privées',
    'api_tokens.private_access_help' => 'Le Jeton permet d\'accéder et de modifier les liens privés, les listes, les étiquettes et les notes de n\'importe quel utilisateur en fonction des capacités spécifiées.',
    'api_tokens.abilities' => 'Capacités du Jeton',
    'api_tokens.abilities_select' => 'Sélectionnez les capacités du Jeton...',
    'api_tokens.abilities_help' => 'Sélectionnez toutes les capacités qu\'un Jeton peut avoir. Les capacités ne pourront plus être modifiées.',
    'api_tokens.ability_private_access' => 'Le Jeton peut accéder à des données privées',

    'api_tokens.revoke' => 'Révoquer le Jeton',
    'api_tokens.revoke_confirm' => 'Voulez-vous vraiment révoquer ce Jeton ? Cette étape ne peut pas être annulée et le jeton ne peut pas être récupéré.',
    'api_tokens.revoke_successful' => 'Le Jeton a été révoqué avec succès.',

];
