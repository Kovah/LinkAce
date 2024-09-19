<?php
return [
    'settings' => 'Instellingen',
    'user_settings' => 'Gebruikersinstellingen',
    'account_settings' => 'Accountinstellingen',
    'app_settings' => 'Applicatie-instellingen',
    'system_settings' => 'Systeeminstellingen',
    'guest_settings' => 'Gastgebruikersinstellingen',

    'locale' => 'Taal',
    'timezone' => 'Tijdzone',
    'date_format' => 'Datumnotatie',
    'time_format' => 'Tijdnotatie',
    'listitem_count' => 'Aantal resultaten in lijsten',

    'links_new_tab' => 'Externe links in nieuwe tabbladen openen',

    'markdown_for_text' => 'Markdown voor omschrijvingen en notities inschakelen',

    'privacy' => 'Privacy',
    'profile_privacy' => 'De volgende instellingen zijn van toepassing op je gebruikersprofiel dat zichtbaar is voor gasten.',
    'profile_is_public' => 'Profiel is openbaar',
    'default_visibility_help' => 'De volgende instellingen bepalen de standaard zichtbaarheid voor links, lijsten, notities en tags wanneer er nieuwe worden toegevoegd.',
    'links_default_visibility' => 'Standaard zichtbaarheid koppelingen',
    'notes_default_visibility' => 'Standaard zichtbaarheid koppelingen',
    'lists_default_visibility' => 'Standaard zichtbaarheid lijsten',
    'tags_default_visibility' => 'Standaard zichtbaarheid tags',

    'archive_backups' => 'Wayback Machine-back-ups',
    'archive_backups_help' => 'Indien ingeschakeld, vertelt LinkAce de <a href="https://archive.org/">Wayback Machine</a> om een back-up van je links te maken. De Wayback Machine wordt gevoed door het Internet Archive, een non-profit organisatie. Overweeg <a href="https://archive.org/donate/">te doneren aan het Internet Archive</a>.',
    'archive_backups_enabled' => 'Back-ups inschakelen',
    'archive_backups_enabled_help' => 'Indien ingeschakeld worden niet-privékoppelingen opgeslagen door het Internet Archive.',
    'archive_private_backups_enabled' => 'Back-ups inschakelen voor privékoppelingen',
    'archive_private_backups_enabled_help' => 'Als dit is ingeschakeld, worden privékoppelingen ook opgeslagen. Back-ups moeten zijn ingeschakeld.',

    'link_display_mode' => 'Weergavemodus koppelingen',
    'display_mode' => 'Koppelingen weergeven als',
    'display_mode_list_simple' => 'Toon koppelingen als eenvoudige lijst',
    'display_mode_list_detailed' => 'Toon koppelingen als gedetailleerde lijst',
    'display_mode_cards' => 'Toon koppelingen als kaarten',

    'sharing' => 'Koppelingen delen',
    'guest_sharing' => 'Gastkoppelingdeling',
    'sharing_help' => 'Schakel alle diensten in die u wilt weergeven voor koppelingen om ze gemakkelijk te delen met één klik.',
    'sharing_toggle' => 'Alles aan/uit',

    'darkmode_setting' => 'Donkere modus',
    'darkmode_help' => 'Je kunt kiezen om dit permanent of automatisch in te schakelen op basis van je apparaatinstellingen. (<small>Controleer <a href="https://caniuse.com/#search=prefers-color-scheme">hier</a> of je browser automatische detectie ondersteunt</small>)',
    'darkmode_disabled' => 'Uitgeschakeld',
    'darkmode_auto' => 'Automatisch',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Instellingen opslaan',
    'settings_saved' => 'Instellingen succesvol bijgewerkt!',

    'bookmarklet' => 'Bookmarklet',
    'bookmarklet_button' => 'Sleep dit naar je bladwijzers of klik met de rechtermuisknop en sla het op als een bladwijzer',
    'bookmarklet_help' => 'Voeg deze bladwijzer toe aan je browser om snel koppelingen toe te voegen van de sites die je bezoekt zonder LinkAce handmatig te hoeven openen.',

    'change_password' => 'Wijzig wachtwoord',
    'old_password' => 'Oud wachtwoord',
    'new_password' => 'Nieuw wachtwoord',
    'new_password2' => 'Herhaal het nieuwe wachtwoord',
    'password_updated' => 'Wachtwoord succesvol gewijzigd!',
    'old_password_invalid' => 'Het oude wachtwoord is onjuist!',

    'two_factor_auth' => 'Tweestapsverificatie',
    'two_factor_enable' => 'Tweestapsverificatie inschakelen',
    'two_factor_disable' => 'Tweestapsverificatie uitschakelen',
    'two_factor_setup_app' => 'Tweestapsverificatie is ingeschakeld. Configureer je verificatieapparaat nu door de volgende QR-code te scannen.',
    'two_factor_setup_url' => 'Werkt de QR-code niet? Je kunt deze URL ook direct gebruiken.',
    'two_factor_recovery_codes' => 'Sla deze herstelcodes op in een veilige wachtwoordmanager. Deze kunnen worden gebruikt om de toegang tot je account te herstellen als je tweestapsverificatieapparaat verloren gaat.',
    'two_factor_recovery_codes_view' => 'Herstelcodes bekijken',
    'two_factor_regenerate_recovery_codes' => 'Nieuwe herstelcodes genereren',

    'page_title' => 'Paginatitel',
    'guest_access' => 'Gastgebruiker inschakelen',
    'guest_access_help' => 'Indien ingeschakeld, kunnen gasten alle koppelingen zien die niet privé zijn.',
    'custom_header_content' => 'Aangepaste header-inhoud',
    'custom_header_content_help' => 'Inhoud die hier is ingevoerd zal voor de &lt;/head&gt; tag op alle LinkAce sites worden geplaatst. Nuttig om analytics of aanpassingsscripts te plaatsen. Let op: de inhoud wordt letterlijk overgenomen en kan de site kapotmaken!',

    'cron_token' => 'Cron-token',
    'cron_token_generate' => 'Token genereren',
    'cron_token_generate_confirm' => 'Wil je echt een nieuw token genereren?',
    'cron_token_help' => 'Het cron-token is nodig om de cron-dienst uit te voeren die controleert op defecte koppelingen of backups uitvoert.',
    'cron_token_url' => 'Richt uw cron-taak naar het volgende webadres: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Let op: als je al een cron-token hebt zal het genereren van een nieuwe token alle bestaande integraties verbreken!',
    'cron_token_generate_failure' => 'Er kon geen nieuw cron-token worden gegenereerd. Controleer je browserconsole en applicatielogboeken voor meer informatie.',
    'cron_token_auth_failure' => 'Het verstrekte cron-token is ongeldig',
    'cron_execute_successful' => 'Cron-taak succesvol uitgevoerd',

    'update_check' => 'Controleren op updates',
    'update_check_running' => 'Bezig met controleren op updates...',
    'update_check_version_found' => 'Update gevonden. Versie #VERSION# is beschikbaar.',
    'update_check_success' => 'Geen update gevonden.',
    'update_check_failed' => 'Kon niet controleren op updates.',

    'guest_settings_info' => 'De volgende instellingen zijn van toepassing op gasten die uw site bezoeken, als gasttoegang is ingeschakeld.',
];
