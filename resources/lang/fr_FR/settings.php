<?php
return [
    'settings' => 'Paramètres',
    'user_settings' => 'Paramètres Utilisateur',
    'account_settings' => 'Paramètres Compte',
    'app_settings' => 'Paramètres Application',
    'system_settings' => 'Paramètres Système',
    'guest_settings' => 'Paramètres Invité',

    'language' => 'Langue',
    'timezone' => 'Fuseau horaire',
    'date_format' => 'Format Date',
    'time_format' => 'Format Heure',
    'listitem_count' => 'Nombre d\'Éléments dans les Listes',

    'links_new_tab' => 'Ouvrir les liens externes dans de nouveaux onglets',

    'markdown_for_text' => 'Activer Markdown pour les descriptions et les notes',

    'privacy' => 'Confidentialité',
    'links_private_default' => 'Liens Privés par défaut',
    'links_private_default_help' => 'Activer ceci rendra tous les nouveaux liens privés par défaut',
    'notes_private_default' => 'Notes Privées par défaut',
    'notes_private_default_help' => 'Activer ceci rendra toutes les nouvelles notes privées par défaut',
    'tags_private_default' => 'Étiquettes Privés par défaut',
    'tags_private_default_help' => 'Activer ceci rendra tous les nouvelles étiquettes privées par défaut',
    'lists_private_default' => 'Listes Privées par défaut',
    'lists_private_default_help' => 'Activer ceci rendra toutes les nouvelles listes privées par défaut',

    'archive_backups' => 'Sauvegardes Wayback Machine',
    'archive_backups_help' => 'Si activé, LinkAce dira à la <a href="https://archive.org/">Wayback Machine</a> de sauvegarder vos liens. La Wayback Machine est gérée par l\'Internet Archive, une organisation à but non lucratif. N\'hésitez pas à faire <a href="https://archive.org/donate/">un don à l\'Internet Archive</a>.',
    'archive_backups_enabled' => 'Activer les sauvegardes',
    'archive_backups_enabled_help' => 'Si cette option est activée, les liens non privés seront enregistrés par Internet Archive.',
    'archive_private_backups_enabled' => 'Activer les sauvegardes pour les liens privés',
    'archive_private_backups_enabled_help' => 'Si activé, les liens privés seront également enregistrés. Les sauvegardes doivent être activées.',

    'display_mode' => 'Afficher les liens comme',
    'display_mode_list_detailed' => 'liste avec de nombreux détails',
    'display_mode_list_simple' => 'liste avec peu de détails',
    'display_mode_cards' => 'cartes avec moins de détails',

    'sharing' => 'Partage de Liens',
    'sharing_help' => 'Activer tous les services que vous voulez afficher, pour pouvoir partager les liens facilement en un clic.',
    'sharing_toggle' => 'Activer/désactiver tout',

    'darkmode' => 'Mode sombre',
    'darkmode_help' => 'Vous pouvez choisir d\'activer définitivement ou automatiquement en fonction des paramètres de votre appareil. (<small>Cocher <a href="https://caniuse.com/#search=prefers-color-scheme">ici</a> si votre navigateur prend en charge la détection automatique</small>)',
    'darkmode_disabled' => 'Désactivé',
    'darkmode_auto' => 'Automatiquement',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Enregistrer Paramètres',
    'settings_saved' => 'Paramètres mis à jour avec succès !',

    'bookmarklet' => 'Bookmarklet',
    'bookmarklet_button' => 'Faites glisser ceci dans vos Signets ou cliquez avec le bouton droit et enregistrez-le comme un signet',
    'bookmarklet_help' => 'Ajoutez ce Bookmarklet à votre navigateur pour ajouter rapidement des liens à partir des sites que vous visitez sans avoir à ouvrir LinkAce manuellement.',

    'change_password' => 'Changer Mot de passe',
    'old_password' => 'Ancien Mot de passe',
    'new_password' => 'Nouveau Mot de passe',
    'new_password2' => 'Répéter nouveau Mot de passe',
    'password_updated' => 'Mot de passe modifié avec succès !',
    'old_password_invalid' => 'L\'ancien mot de passe n\'est pas valide !',

    'two_factor_auth' => 'Authentification Double Facteur',
    'two_factor_enable' => 'Activer Authentification Double Facteur',
    'two_factor_disable' => 'Désactiver Authentification Double Facteur',
    'two_factor_setup_app' => 'L\'authentification à double facteur est activée. Veuillez configurer votre périphérique d\'authentification maintenant en scannant le QR code suivant.',
    'two_factor_setup_url' => 'Le QR code ne fonctionne pas ? Vous pouvez également utiliser cette URL directement.',
    'two_factor_recovery_codes' => 'Stockez ces codes de récupération dans un gestionnaire de mots de passe sécurisé. Ils peuvent être utilisés pour récupérer l\'accès à votre compte si votre périphérique d\'authentification à double facteur est perdu.',
    'two_factor_recovery_codes_view' => 'Voir Codes de Récupération',
    'two_factor_regenerate_recovery_codes' => 'Générer nouveaux Codes de Récupération',

    'api_token' => 'Jeton API',
    'api_token_generate' => 'Générer Jeton',
    'api_token_generate_confirm' => 'Voulez-vous vraiment générer un nouveau jeton ?',
    'api_token_help' => 'Le jeton API peut être utilisé pour accéder à LinkAce depuis d\'autres applications ou scripts.',
    'api_token_generate_info' => 'Attention : si vous avez déjà un jeton API, la génération d\'un nouveau jeton va casser toutes les intégrations existantes !',
    'api_token_generate_failure' => 'Un nouveau jeton API n\'a pas pu être généré. Veuillez vérifier la console de votre navigateur et les journaux de votre application pour plus d\'informations.',

    'sys_page_title' => 'Titre Page',
    'sys_guest_access' => 'Activer Accès Invité',
    'sys_guest_access_help' => 'Si activé, l\'invité sera en mesure de voir tous les liens qui ne sont pas privés.',

    'cron_token' => 'Jeton Cron',
    'cron_token_generate' => 'Générer Jeton',
    'cron_token_generate_confirm' => 'Voulez-vous vraiment générer un nouveau jeton ?',
    'cron_token_help' => 'Le jeton cron est nécessaire pour exécuter le service cron qui vérifie les liens morts ou exécute des sauvegardes.',
    'cron_token_url' => 'Pointez votre cron vers l\'URL suivante : <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Attention : si vous avez déjà un jeton cron, la génération d\'un nouveau jeton va casser la tâche cron existante !',
    'cron_token_generate_failure' => 'Un nouveau jeton cron n\'a pas pu être généré. Veuillez vérifier la console de votre navigateur et les journaux de votre application pour plus d\'informations.',
    'cron_token_auth_failure' => 'Le jeton de cron fourni est invalide',
    'cron_execute_successful' => 'Cron exécuté avec succès',

    'update_check' => 'Vérification Mise à jour',
    'update_check_running' => 'Vérification des mises à jour...',
    'update_check_version_found' => 'Mise à jour trouvée. La version #VERSION# est disponible.',
    'update_check_success' => 'Aucune mise à jour trouvée.',
    'update_check_failed' => 'Impossible de vérifier les mises à jour.',

    'guest_settings_info' => 'Les paramètres suivants s\'appliqueront aux visiteurs de votre site, si l\'accès invité est activé.',
];
