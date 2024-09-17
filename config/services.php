<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // OAuth and SSO

    'auth0' => [
        'enabled' => env('OAUTH_AUTH0_ENABLED', false),
        'base_url' => env('OAUTH_AUTH0_BASE_URL'),
        'client_id' => env('OAUTH_AUTH0_CLIENT_ID'),
        'client_secret' => env('OAUTH_AUTH0_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/auth0/callback',
    ],

    'authentik' => [
        'enabled' => env('OAUTH_AUTHENTIK_ENABLED', false),
        'base_url' => env('OAUTH_AUTHENTIK_BASE_URL'),
        'client_id' => env('OAUTH_AUTHENTIK_CLIENT_ID'),
        'client_secret' => env('OAUTH_AUTHENTIK_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/authentik/callback',
    ],

    'azure' => [
        'enabled' => env('OAUTH_AZURE_ENABLED', false),
        'client_id' => env('OAUTH_AZURE_CLIENT_ID'),
        'client_secret' => env('OAUTH_AZURE_CLIENT_SECRET'),
        'tenant' => env('OAUTH_AZURE_TENANT_ID'),
        'proxy' => env('PROXY'),
        'redirect' => '/auth/oauth/azure/callback',
    ],

    'cognito' => [
        'enabled' => env('OAUTH_COGNITO_ENABLED', false),
        'host' => env('OAUTH_COGNITO_HOST'),
        'client_id' => env('OAUTH_COGNITO_CLIENT_ID'),
        'client_secret' => env('OAUTH_COGNITO_CLIENT_SECRET'),
        'redirect' => env('OAUTH_COGNITO_CALLBACK_URL'),
        'scope' => explode(',', env('OAUTH_COGNITO_LOGIN_SCOPE', '')),
        'logout_uri' => env('OAUTH_COGNITO_SIGN_OUT_URL'),
    ],

    'fusionauth' => [
        'enabled' => env('OAUTH_FUSIONAUTH_ENABLED', false),
        'base_url' => env('OAUTH_FUSIONAUTH_BASE_URL'),
        'client_id' => env('OAUTH_FUSIONAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_FUSIONAUTH_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/fusionauth/callback',
    ],

    'google' => [
        'enabled' => env('OAUTH_GOOGLE_ENABLED', false),
        'client_id' => env('OAUTH_GOOGLE_CLIENT_ID'),
        'client_secret' => env('OAUTH_GOOGLE_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/{provider}/callback',
    ],

    'github' => [
        'enabled' => env('OAUTH_GITHUB_ENABLED', false),
        'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
        'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/github/callback',
    ],

    'gitlab' => [
        'enabled' => env('OAUTH_GITLAB_ENABLED', false),
        'host' => env('OAUTH_GITLAB_HOST'),
        'client_id' => env('OAUTH_GITLAB_CLIENT_ID'),
        'client_secret' => env('OAUTH_GITLAB_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/gitlab/callback',
    ],

    'keycloak' => [
        'enabled' => env('OAUTH_KEYCLOAK_ENABLED', false),
        'client_id' => env('OAUTH_KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('OAUTH_KEYCLOAK_CLIENT_SECRET'),
        'realms' => env('OAUTH_KEYCLOAK_REALM'),
        'redirect' => '/auth/oauth/keycloak/callback',
    ],

    'oidc' => [
        'enabled' => env('OAUTH_OIDC_ENABLED', false),
        'base_url' => env('OAUTH_OIDC_BASE_URL'),
        'client_id' => env('OAUTH_OIDC_CLIENT_ID'),
        'client_secret' => env('OAUTH_OIDC_CLIENT_SECRET'),
        'scopes' => env('OAUTH_OIDC_SCOPES'),
        'redirect' => '/auth/oauth/oidc/callback',
    ],

    'okta' => [
        'enabled' => env('OAUTH_OKTA_ENABLED', false),
        'base_url' => env('OAUTH_OKTA_BASE_URL'),
        'client_id' => env('OAUTH_OKTA_CLIENT_ID'),
        'client_secret' => env('OAUTH_OKTA_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/okta/callback',
    ],

    'zitadel' => [
        'enabled' => env('OAUTH_ZITADEL_ENABLED', false),
        'client_id' => env('OAUTH_ZITADEL_CLIENT_ID'),
        'client_secret' => env('OAUTH_ZITADEL_CLIENT_SECRET'),
        'base_url' => env('OAUTH_ZITADEL_BASE_URL'),
        'organization_id' => env('OAUTH_ZITADEL_ORGANIZATION_ID'),
        'project_id' => env('OAUTH_ZITADEL_PROJECT_ID'),
        'post_logout_redirect_uri' => env('OAUTH_ZITADEL_POST_LOGOUT_REDIRECT_URI', '/'),
        'redirect' => '/auth/oauth/zitadel/callback',
    ],

];
