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

    'register' => 'Register',
    'register_welcome' => 'Welcome to LinkAce! You have been invited to join this social bookmarking tool. Please select a user name and a password. After the successful registration, you will be redirected to the dashboard.',

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'confirm_title' => 'Confirmation required',
    'confirm' => 'Please confirm this action using your current password.',
    'confirm_action' => 'Confirm action',

    'two_factor' => 'Two Factor Authentication',
    'two_factor_check' => 'Please enter the one-time-password provided by your Two Factor Authentication app now.',
    'two_factor_with_recovery' => 'Authenticate with Recovery Code',

    'api_tokens' => 'API Tokens',
    'api_tokens.no_tokens_found' => 'No API Tokens found.',
    'api_tokens.generate' => 'Generate a new API Token',
    'api_tokens.generate_short' => 'Generate Token',
    'api_tokens.generate_help' => 'API tokens are used to authenticate yourself when using the LinkAce API.',
    'api_tokens.generated_successfully' => 'The API token was generated successfully: <code>:token</code>',
    'api_tokens.generated_help' => 'Please store this token in a safe place. It is <strong>not</strong> possible to recover your token if you lose it.',
    'api_tokens.name' => 'Token name',
    'api_tokens.name_help' => 'Choose a name for your token. The name can only contain alpha-numeric characters, dashes, and underscores. Helpful if you want to create separate tokens for different use cases or applications.',

    'api_token_system' => 'System API Token',
    'api_tokens_system' => 'System API Tokens',
    'api_tokens.generate_help_system' => 'API tokens are used to access the LinkAce API from other applications or scripts. By default, only public or internal data is accessible, but tokens can be granted additional access to private data if needed.',
    'api_tokens.private_access' => 'Token can access private data',
    'api_tokens.private_access_help' => 'The token can access and change private links, lists, tags and notes of any user based on the specified abilities.',
    'api_tokens.abilities' => 'Token abilities',
    'api_tokens.abilities_select' => 'Select token abilities...',
    'api_tokens.abilities_help' => 'Select all abilities a token can have. Abilities cannot be changed later.',
    'api_tokens.ability_private_access' => 'Token can access private data',

    'api_tokens.revoke' => 'Revoke token',
    'api_tokens.revoke_confirm' => 'Do you really want to revoke this token? This step cannot be undone and the token cannot be recovered.',
    'api_tokens.revoke_successful' => 'The token was revoked successfully.',

    'sso_wrong_provider' => 'Unable to login with :currentProvider. Please use :userProvider to login, or contact your administrator for help.',

    'sso' => [
        'auth0' => 'Auth0',
        'authentik' => 'Authentik',
        'azure' => 'Azure',
        'cognito' => 'Cognito',
        'fusionauth' => 'FusionAuth',
        'google' => 'Google',
        'github' => 'GitHub',
        'gitlab' => 'GitLab',
        'keycloak' => 'Keycloak',
        'oidc' => 'OIDC',
        'okta' => 'Okta',
        'zitadel' => 'Zitadel',
    ],
];
