<?php
return [
    'settings' => 'Innstillinger',
    'user_settings' => 'Brukerinnstillinger',
    'account_settings' => 'Kontoinnstillinger',
    'app_settings' => 'Programinnstillinger',
    'system_settings' => 'Systeminnstillinger',
    'guest_settings' => 'Gjesteinnstillinger',

    'language' => 'Språk',
    'timezone' => 'Tidssone',
    'date_format' => 'Datoformat',
    'time_format' => 'Tidsformat',
    'listitem_count' => 'Antall elementer i lister',

    'links_new_tab' => 'Åpne eksterne lenker i nye faner',

    'markdown_for_text' => 'Aktiver Markdown for beskrivelser og notater',

    'privacy' => 'Personvern',
    'links_private_default' => 'Private lenker som standard',
    'links_private_default_help' => 'Aktivering av dette vil gjøre at alle nye lenker blir satt som privat som standard',
    'notes_private_default' => 'Private notater som standard',
    'notes_private_default_help' => 'Aktivering av dette vil gjøre at alle nye notater blir satt som privat som standard',
    'tags_private_default' => 'Privat etikett som standard',
    'tags_private_default_help' => 'Aktivering av dette vil gjøre at alle nye etiketter blir satt som privat som standard',
    'lists_private_default' => 'Private lister som standard',
    'lists_private_default_help' => 'Aktivering av dette vil gjøre at alle nye lister blir satt som privat som standard',

    'archive_backups' => 'Wayback Machine sikkerhetskopier',
    'archive_backups_help' => 'Hvis aktivert, så vil LinkAce fortelle <a href="https://archive.org/">Wayback Machine</a> om å sikkerhetskopiere dine lenker. Wayback Machine drives av Internet Archive, en ideell organisasjon. Vennligst vurder <a href="https://archive.org/donate/">å donere til Internett Archive</a>.',
    'archive_backups_enabled' => 'Aktiver sikkerhetskopier',
    'archive_backups_enabled_help' => 'Hvis aktivert, så vil ikke-private lenker bli lagret av Internet Archive.',
    'archive_private_backups_enabled' => 'Aktiver sikkerhetskopier for private lenker',
    'archive_private_backups_enabled_help' => 'Dersom aktivert, så vil private lenker også bli lagret. Sikkerhetskopiering må være aktivert.',

    'display_mode' => 'Vis lenker som',
    'display_mode_list_detailed' => 'liste med mange detaljer',
    'display_mode_list_simple' => 'liste med mindre detaljer',
    'display_mode_cards' => 'kort med mindre detaljer',
    'display_mode_cards_detailed' => 'kort med mange detaljer',

    'sharing' => 'Lenkedeling',
    'sharing_help' => 'Aktiverer alle tjenester som du vil bruke mot lenker, for å kunne dele dem enkelt med ett klikk.',
    'sharing_toggle' => 'Slå alle av/på',

    'darkmode' => 'Mørkmodus',
    'darkmode_help' => 'Du kan enten velge å slå på den permanent eller automatisk basert på enhetsinnstillingene dine. (<small>Sjekk <a href="https://caniuse.com/#search=prefers-color-scheme">her</a> om din nettleser støtter automatisk gjenkjenning</small>)',
    'darkmode_disabled' => 'Deaktivert',
    'darkmode_auto' => 'Automatisk',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Lagre innstillinger',
    'settings_saved' => 'Innstillinger oppdatert!',

    'bookmarklet' => 'Bokmerketillegget',
    'bookmarklet_button' => 'Dra dette til dine bokmerker eller høyreklikk og lagre det som et bokmerke',
    'bookmarklet_help' => 'Legg til dette bokmerketillegget i nettleseren din, for å raskt legge til lenker fra sidene du besøker uten å måtte åpne LinkAce manuelt.',

    'change_password' => 'Endre passord',
    'old_password' => 'Gammelt passord',
    'new_password' => 'Nytt passord',
    'new_password2' => 'Gjenta nytt passord',
    'password_updated' => 'Passordet er endret!',
    'old_password_invalid' => 'Det gamle passordet er ikke gyldig!',

    'two_factor_auth' => 'To-faktor autentisering',
    'two_factor_enable' => 'Aktiver to-faktor autentisering',
    'two_factor_disable' => 'Deaktiver to-faktor autentisering',
    'two_factor_setup_app' => 'To-faktor autentisering er aktivert. Vennligst konfigurer autentiseringsenheten din nå ved å scanne følgende QR-kode.',
    'two_factor_setup_url' => 'QR-kode fungerer ikke? Du kan også bruke denne nettadressen direkte.',
    'two_factor_recovery_codes' => 'Lagre disse gjenopprettingskodene i en sikker passordbehandler. De kan brukes til å gjenopprette tilgangen til kontoen din dersom du har mistet autentiseringsenheten din.',
    'two_factor_recovery_codes_view' => 'Vis gjenopprettingskoder',
    'two_factor_regenerate_recovery_codes' => 'Generer nye gjenopprettingskoder',

    'api_token' => 'API-token',
    'api_token_generate' => 'Opprett token',
    'api_token_generate_confirm' => 'Vil du virkelig generere en ny token?',
    'api_token_help' => 'API-tokenet kan brukes til å få tilgang til LinkAce fra andre applikasjoner eller skript.',
    'api_token_generate_info' => 'Advarsel: Dersom du allerede har en API-token, så vil generering av en ny bryte alle eksisterende integrasjoner!',
    'api_token_generate_failure' => 'Et nytt API-token kunne ikke genereres. Vennligst sjekk nettleserkonsollet og applikasjonsloggene for ytterligere informasjon.',

    'sys_page_title' => 'Sidetittel',
    'sys_guest_access' => 'Aktiver gjestetilgang',
    'sys_guest_access_help' => 'Dersom aktivert, så vil gjester kunne se alle lenker som ikke er private.',
    'sys_custom_header_content' => 'Egendefinert topptekstinnhold',
    'sys_custom_header_content_help' => 'Innhold som er skrevet inn her vil bli plassert før &lt;/head&gt; -taggen på alle LinkAce nettstedene. Nyttig for å plassere analyse- eller tilpasningsskript. Forsiktighet: Innhold er ikke validert og kan ødelegge denne siden!',

    'cron_token' => 'Cron token',
    'cron_token_generate' => 'Opprett token',
    'cron_token_generate_confirm' => 'Vil du virkelig generere en ny token?',
    'cron_token_help' => 'En cron token er nødvendig for å kjøre cron-tjenesten som sjekker for døde lenker eller kjøre sikkerhetskopiering.',
    'cron_token_url' => 'Pek din cron til følgende nettadresse: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Advarsel: Dersom du allerede har en cron token, så vil generering av en ny stoppe den eksisterende cron jobben!',
    'cron_token_generate_failure' => 'En nytt cron token kunne ikke genereres. Vennligst sjekk nettleserkonsollet og applikasjonsloggene for ytterligere informasjon.',
    'cron_token_auth_failure' => 'Den oppgitte cron token er ugyldig',
    'cron_execute_successful' => 'Cron jobber er kjørt vellykket',

    'update_check' => 'Sjekk for oppdateringer',
    'update_check_running' => 'Ser etter oppdateringer...',
    'update_check_version_found' => 'Oppdatering funnet. Versjon #VERSION# er tilgjengelig.',
    'update_check_success' => 'Ingen oppdatering funnet.',
    'update_check_failed' => 'Kunne ikke se etter oppdateringer.',

    'guest_settings_info' => 'Følgende innstillinger vil gjelde for gjester som besøker siden din, hvis gjestetilgang er aktivert.',
];
