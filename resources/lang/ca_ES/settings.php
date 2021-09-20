<?php
return [
    'settings' => 'Ajustaments',
    'user_settings' => 'Ajustaments de l\'usuari',
    'account_settings' => 'Configuració del compte',
    'app_settings' => 'Configuració de l\'aplicació',
    'system_settings' => 'Configuració del Sistema',
    'guest_settings' => 'Ajustaments del convidat',

    'language' => 'Llengua',
    'timezone' => 'Fus horari',
    'date_format' => 'Format de la data',
    'time_format' => 'Format de temps',
    'listitem_count' => 'Nombre d\'Elments de les Llistes',

    'links_new_tab' => 'Obrir enllaços externs en pestanyes noves',

    'markdown_for_text' => 'Habilitar Markdown per descripcions e notes',

    'privacy' => 'Privacitat',
    'links_private_default' => 'Enlaços privats per defecte',
    'links_private_default_help' => 'Habilitar això farà que tots els nous enllaços siguin privats per defecte',
    'notes_private_default' => 'Notes privades per defecte',
    'notes_private_default_help' => 'Habilitar això farà que totes noves notes siguin privades per defecte',
    'tags_private_default' => 'Etiquetes privades per defecte',
    'tags_private_default_help' => 'Habilitar això farà que totes noves etiquetes siguin privades per defecte',
    'lists_private_default' => 'Llistes privades per defecte',
    'lists_private_default_help' => 'Habilitar això farà que totes noves llistes siguin privades per defecte',

    'archive_backups' => 'Backups de Wayback Machine',
    'archive_backups_help' => 'Si està activat, LinkAce li dirà a la <a href="https://archive.org/">Máquina Wayback</a> que faci una còpia de seguretat dels teus enllaços. La Wayback Machine està alimentada per l\'Internet Archive, una organització sense ànims de lucre. Si us plau considera <a href="https://archive.org/donate/"> fer una donció a Internet Archive</a>.',
    'archive_backups_enabled' => 'Habilitar còpies de seguretat',
    'archive_backups_enabled_help' => 'Si està habilitat, els enllaços no privats es desaran per Internet Archive.',
    'archive_private_backups_enabled' => 'Habilitar còpies de seguretat per enllaços privats',
    'archive_private_backups_enabled_help' => 'Si está activat, també es desaren els enllaços privats. Les còpies de seguretat han d\'estar habilitades.',

    'display_mode' => 'Mostrar enllaços com',
    'display_mode_list_detailed' => 'llista amb molts detalls',
    'display_mode_list_simple' => 'llista amb pocs detalls',
    'display_mode_cards' => 'tarja amb menys detalls',

    'sharing' => 'Compartir enllaços',
    'sharing_help' => 'Activa tots els serveis que vulguis mostra pels enllaços, per poder compartir-los fàcilment amb un sol clic.',
    'sharing_toggle' => 'Activar/desactivar tot',

    'darkmode' => 'Modo fosc',
    'darkmode_help' => 'Pots optar per activar permanentement o automàticamente basant-se en la configuració del teu dispositiu. (<small>Comprova <a href="https://caniuse.com/#search=prefers-color-scheme">aquí</a> si el teu navegador suporta detecció automàtica</small>)',
    'darkmode_disabled' => 'Desactiva',
    'darkmode_auto' => 'Automàticament',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Desa la configuració',
    'settings_saved' => 'Configuració actualitzada amb èxit!',

    'bookmarklet' => 'Marcador',
    'bookmarklet_button' => 'Arrossega això als teus marcadors o fes clic dret i desa-ho com un marcador',
    'bookmarklet_help' => 'Afegeix aquest marcador al teu navegador per afegir ràpidament enllaços des dels llocs que visita sense tenir que obrir LinkAce manualment.',

    'change_password' => 'Canvia la contrasenya',
    'old_password' => 'Contrasenya antiga',
    'new_password' => 'Nova contrasenya',
    'new_password2' => 'Repeteix la nova contrasenya',
    'password_updated' => 'Contrasenya canviada amb èxit!',
    'old_password_invalid' => 'La contrasenya antiga no era correcta!',

    'two_factor_auth' => 'Doble Factor d\'Autenticació',
    'two_factor_enable' => 'Habilita l`autenticació de doble factor',
    'two_factor_disable' => 'Deshabilitar l`autenticació de doble factor',
    'two_factor_setup_app' => 'L\'autenticació de dos factors está habilitada. Si us plau, configura el teu dispositiu d\'autenticació ara escanejant el següent codi QR.',
    'two_factor_setup_url' => 'El codi QR no funciona? També pots fer servir aquesta URL directament.',
    'two_factor_recovery_codes' => 'Emmagatzeni aquests codis de recuperació en un gestor de contrasenyes segur. Es poden fer servir per recuperar l\'accés al teu compte si el dispositiu de doble factor es perd.',
    'two_factor_recovery_codes_view' => 'Veure codis de recuperació',
    'two_factor_regenerate_recovery_codes' => 'Generar nous codis de recuperació',

    'api_token' => 'API Token',
    'api_token_generate' => 'Generar token',
    'api_token_generate_confirm' => 'Realment dessitja generar un nou token?',
    'api_token_help' => 'El token de l\'API es pot fer servir per accedir a LinkAce des de altres aplicacions o scripts.',
    'api_token_generate_info' => 'Atenció: ¡Si ja tens un token de API, generar u nou pot trencar totes les integracions existents!',
    'api_token_generate_failure' => 'No s\'ha pogut generar un nou token de l\'API. Si us plau, comprova la consola de su navegador i els registres de l\'aplicació per obteier més informació.',

    'sys_page_title' => 'Tí­tol de la pàgina',
    'sys_guest_access' => 'Habilitar l\'accés a convidats',
    'sys_guest_access_help' => 'Si está activat, el convidat pot veure tots els enllaços que no son privats.',

    'cron_token' => 'Token de Cron',
    'cron_token_generate' => 'Generar token',
    'cron_token_generate_confirm' => 'Realment dessitja generar un nou token?',
    'cron_token_help' => 'El token cron es necessari per executar el servei cron que comprova si existeixen enllaços morts o còpies de seguretat.',
    'cron_token_url' => 'Apunta el teu cron a la següent URL: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Atenció: ¡Si ja tens un cron del token, generar u nou pot trencar el cron existent!',
    'cron_token_generate_failure' => 'No s\'ha pogut generar un nou cron de token. Si us plau, comprova la consola de su navegador i els registres de l\'aplicació per obteier més informació.',
    'cron_token_auth_failure' => 'El token de cron proporcionat no és vàlid',
    'cron_execute_successful' => 'Cron executat correctament',

    'update_check' => 'Comprovació d\'actualitzacions',
    'update_check_running' => 'Comprovant si hi ha actualitzacions disponibles...',
    'update_check_version_found' => 'Actualització encontrada. Versió #VERSION# disponible.',
    'update_check_success' => 'No s\'han trobat actualitzacions.',
    'update_check_failed' => 'No s\'ha pogut cercar actualitzacions.',

    'guest_settings_info' => 'Els. següents ajustaments s\'aplicarán als convidats que visitin el teu lloc, si l\'accés a convidats está habilitat.',
];
