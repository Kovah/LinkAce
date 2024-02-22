<?php
return [
    'settings' => 'Settings',
    'user_settings' => 'User Settings',
    'account_settings' => 'Account Settings',
    'app_settings' => 'Application Settings',
    'system_settings' => 'System Settings',
    'guest_settings' => 'Guest Settings',

    'locale' => 'Language',
    'timezone' => 'Timezone',
    'date_format' => 'Date Format',
    'time_format' => 'Time Format',
    'listitem_count' => 'Number of Items in Lists',

    'links_new_tab' => 'Open external links in new tabs',

    'markdown_for_text' => 'Enable Markdown for descriptions and notes',

    'privacy' => 'Privacy',
    'profile_privacy' => 'The following settings apply to your user profile which is visible to guests.',
    'profile_is_public' => 'Profile is public',
    'default_visibility_help' => 'The following settings define the default visibility for Links, Lists, Notes and Tags when adding new ones.',
    'links_default_visibility' => 'Default Links visibility',
    'notes_default_visibility' => 'Default Notes visibility',
    'lists_default_visibility' => 'Default Lists visibility',
    'tags_default_visibility' => 'Default Tags visibility',

    'archive_backups' => 'Wayback Machine backups',
    'archive_backups_help' => 'If enabled, LinkAce will tell the <a href="https://archive.org/">Wayback Machine</a> to backup your links. The Wayback Machine is powered by the Internet Archive, a non-profit organization. Please consider <a href="https://archive.org/donate/">donating to the Internet Archive</a>.',
    'archive_backups_enabled' => 'Enable backups',
    'archive_backups_enabled_help' => 'If enabled, non-private links will be saved by the Internet Archive.',
    'archive_private_backups_enabled' => 'Enable backups for private links',
    'archive_private_backups_enabled_help' => 'If enabled, private links will also be saved. Backups must be enabled.',

    'link_display_mode' => 'Link Display Mode',
    'display_mode' => 'Display links as',
    'display_mode_list_simple' => 'Display Links as simple List',
    'display_mode_list_detailed' => 'Display Links as detailed List',
    'display_mode_cards' => 'Display Links as Cards',

    'sharing' => 'Link Sharing',
    'guest_sharing' => 'Guest Link Sharing',
    'sharing_help' => 'Enable all services you want to display for links, to be able to share them easily with one click.',
    'sharing_toggle' => 'Toggle all on/off',

    'darkmode_setting' => 'Darkmode',
    'darkmode_help' => 'You can either choose to turn on permanently or automatically based on your device settings. (<small>Check <a href="https://caniuse.com/#search=prefers-color-scheme">here</a> if your browser supports automatic detection</small>)',
    'darkmode_disabled' => 'Disabled',
    'darkmode_auto' => 'Automatically',
    'darkmode_permanent' => 'Permanent',

    'save_settings' => 'Save Settings',
    'settings_saved' => 'Settings successfully updated!',

    'bookmarklet' => 'Bookmarklet',
    'bookmarklet_button' => 'Drag this to your Bookmarks or right-click and save it as a bookmark',
    'bookmarklet_help' => 'Add this Bookmarklet to your browser to quickly add links from the sites you visit without having to open LinkAce manually.',

    'change_password' => 'Change Password',
    'old_password' => 'Old Password',
    'new_password' => 'New Password',
    'new_password2' => 'Repeat new Password',
    'password_updated' => 'Password changed successfully!',
    'old_password_invalid' => 'The old password is not valid!',

    'two_factor_auth' => 'Two Factor Authentication',
    'two_factor_enable' => 'Enable Two Factor Authentication',
    'two_factor_disable' => 'Disable Two Factor Authentication',
    'two_factor_setup_app' => 'Two factor authentication is enabled. Please configure your authentication device now by scanning the following QR code.',
    'two_factor_setup_url' => 'QR code not working? You may also use this URL directly.',
    'two_factor_recovery_codes' => 'Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.',
    'two_factor_recovery_codes_view' => 'View Recovery Codes',
    'two_factor_regenerate_recovery_codes' => 'Generate new Recovery Codes',

    'page_title' => 'Page Title',
    'guest_access' => 'Enable Guest Access',
    'guest_access_help' => 'If enabled, guest will be able to see all links that are not private.',
    'custom_header_content' => 'Custom Header Content',
    'custom_header_content_help' => 'Content entered here will be placed before the &lt;/head&gt; tag on all LinkAce sites. Useful to place analytics or customization scripts. Caution: contents are not escaped and may break the site!',

    'cron_token' => 'Cron Token',
    'cron_token_generate' => 'Generate Token',
    'cron_token_generate_confirm' => 'Do you really want to generate a new token?',
    'cron_token_help' => 'The cron token is needed to run the cron service which checks for dead links or running backups.',
    'cron_token_url' => 'Point your cron to the following URL: <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => 'Caution: If you already have an cron token, generating a new one will break the existing cron job!',
    'cron_token_generate_failure' => 'A new cron token could not be generated. Please check your browser console and application logs for more information.',
    'cron_token_auth_failure' => 'The provided cron token is invalid',
    'cron_execute_successful' => 'Cron successfully executed',

    'update_check' => 'Update Check',
    'update_check_running' => 'Checking for updates...',
    'update_check_version_found' => 'Update found. Version #VERSION# is available.',
    'update_check_success' => 'No update found.',
    'update_check_failed' => 'Could not check for updates.',

    'guest_settings_info' => 'The following settings will apply to guests visiting your site, if guest access is enabled.',
];
