<?php
return [
    'settings' => 'Ajustes',
    'user_settings' => 'Ajustes de usuario',
    'account_settings' => 'Ajustes de la cuenta',
    'app_settings' => 'Ajustes de la aplicación',
    'system_settings' => 'Ajustes del sistema',
    'guest_settings' => 'Ajustes de invitado',

    'language' => 'Idioma',
    'timezone' => 'Zona horaria',
    'date_format' => 'Formato de fecha',
    'time_format' => 'Formato de hora',
    'listitem_count' => 'Número de artículos en listas',

    'links_new_tab' => 'Abrir enlaces externos en pestañas nuevas',

    'markdown_for_text' => 'Habilitar Markdown para descripciones y notas',

    'privacy' => 'Privacidad',
    'links_private_default' => 'Enlaces privados por defecto',
    'links_private_default_help' => 'Habilitar esto hará que todos los nuevos enlaces sean privados por defecto',
    'notes_private_default' => 'Notas privadas por defecto',
    'notes_private_default_help' => 'Habilitar esto hará que todas las nuevas notas sean privadas por defecto',
    'tags_private_default' => 'Etiquetas privadas por defecto',
    'tags_private_default_help' => 'Activar esto hará que todas las nuevas etiquetas sean privadas por defecto',
    'lists_private_default' => 'Listas privadas por defecto',
    'lists_private_default_help' => 'Habilitar esto hará que todas las listas nuevas sean privadas por defecto',

    'archive_backups' => 'Backups de Wayback Machine',
    'archive_backups_help' => 'Si está activado, LinkAce le dirá a la <a href="https://archive.org/">Máquina Wayback</a> que realice una copia de seguridad de tus enlaces. La Wayback Machine está alimentada por el Internet Archive, una organización sin fines de lucro. Por favor considere <a href="https://archive.org/donate/">donar a Internet Archive</a>.',
    'archive_backups_enabled' => 'Habilitar copias de seguridad',
    'archive_backups_enabled_help' => 'Si está habilitado, los enlaces no privados serán guardados por Internet Archive.',
    'archive_private_backups_enabled' => 'Habilitar copias de seguridad para enlaces privados',
    'archive_private_backups_enabled_help' => 'Si está activado, también se guardarán los enlaces privados. Las copias de seguridad deben estar habilitadas.',

    'display_mode' => 'Mostrar enlaces como',
    'display_mode_list_detailed' => 'lista con muchos detalles',
    'display_mode_list_simple' => 'lista con pocos detalles',
    'display_mode_cards' => 'tarjetas con menos detalles',

    'sharing' => 'Compartir enlaces',
    'sharing_help' => 'Activa todos los servicios que quieras mostrar para los enlaces, para poder compartirlos fácilmente con un solo clic.',
    'sharing_toggle' => 'Activar/desactivar todo',

    'darkmode' => 'Modo oscuro',
    'darkmode_help' => 'Puede optar por activar permanentemente o automáticamente basándose en la configuración de su dispositivo. (<small>Compruebe <a href="https://caniuse.com/#search=prefers-color-scheme">aquí</a> si su navegador soporta detección automática</small>)',
    'darkmode_disabled' => 'Deshabilitado',
    'darkmode_auto' => 'Automáticamente',
    'darkmode_permanent' => 'Permanente',

    'save_settings' => 'Guardar ajustes',
    'settings_saved' => '¡Configuración actualizada con éxito!',

    'bookmarklet' => 'Marcador',
    'bookmarklet_button' => 'Arrastre esto a sus marcadores o haga clic derecho y guárdelo como un marcador',
    'bookmarklet_help' => 'Añada este marcador a su navegador para agregar rápidamente enlaces desde los sitios que visita sin tener que abrir LinkAce manualmente.',

    'change_password' => 'Cambiar contraseña',
    'old_password' => 'Contraseña antigua',
    'new_password' => 'Nueva contraseña',
    'new_password2' => 'Repetir nueva contraseña',
    'password_updated' => '¡Contraseña cambiada con éxito!',
    'old_password_invalid' => '¡La contraseña antigua no es válida!',

    'two_factor_auth' => 'Autenticación de dos factores',
    'two_factor_enable' => 'Habilitar autenticación de dos factores',
    'two_factor_disable' => 'Deshabilitar autenticación de dos factores',
    'two_factor_setup_app' => 'La autenticación de dos factores está habilitada. Por favor, configure su dispositivo de autenticación ahora escaneando el siguiente código QR.',
    'two_factor_setup_url' => '¿El código QR no funciona? También puede utilizar esta URL directamente.',
    'two_factor_recovery_codes' => 'Almacene estos códigos de recuperación en un gestor de contraseñas seguro. Pueden ser usados para recuperar el acceso a su cuenta si su dispositivo de autenticación de doble factor se pierde.',
    'two_factor_recovery_codes_view' => 'Ver códigos de recuperación',
    'two_factor_regenerate_recovery_codes' => 'Generar nuevos códigos de recuperación',

    'api_token' => 'API Token',
    'api_token_generate' => 'Generar token',
    'api_token_generate_confirm' => '¿Realmente desea generar un nuevo token?',
    'api_token_help' => 'El token de API se puede utilizar para acceder a LinkAce desde otras aplicaciones o scripts.',
    'api_token_generate_info' => 'Atención: ¡Si ya tienes un token de API, generar uno nuevo romperá todas las integraciones existentes!',
    'api_token_generate_failure' => 'No se ha podido generar un nuevo token de la API. Por favor, compruebe la consola de su navegador y los registros de la aplicación para obtener más información.',

    'sys_page_title' => 'Título de página',
    'sys_guest_access' => 'Activar acceso de invitado',
    'sys_guest_access_help' => 'Si está activado, el invitado podrá ver todos los enlaces que no son privados.',

    'cron_token' => 'Token de Cron',
    'cron_token_generate' => 'Generar token',
    'cron_token_generate_confirm' => '¿Realmente desea generar un nuevo token?',
    'cron_token_help' => 'El token cron es necesario para ejecutar el servicio cron que comprueba si hay enlaces muertos o copias de seguridad.',
    'cron_token_url' => 'Apunte su cron a la siguiente URL: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Precaución: ¡Si ya tienes un token de cron, generar uno nuevo romperá el trabajo de cron existente!',
    'cron_token_generate_failure' => 'No se ha podido generar un nuevo token Cron. Por favor, compruebe la consola de su navegador y los registros de la aplicación para obtener más información.',
    'cron_token_auth_failure' => 'El token de cron proporcionado no es válido',
    'cron_execute_successful' => 'Cron ejecutado correctamente',

    'update_check' => 'Actualizar comprobación',
    'update_check_running' => 'Buscando actualizaciones...',
    'update_check_version_found' => 'Actualización encontrada. Versión #VERSION# disponible.',
    'update_check_success' => 'No se encontraron actualizaciones.',
    'update_check_failed' => 'No se pudo comprobar si hay actualizaciones.',

    'guest_settings_info' => 'Los siguientes ajustes se aplicarán a los invitados que visiten su sitio, si el acceso de invitados está habilitado.',
];
