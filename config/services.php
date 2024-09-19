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
        'enabled' => env('SSO_AUTH0_ENABLED', false),
        'base_url' => env('SSO_AUTH0_BASE_URL'),
        'client_id' => env('SSO_AUTH0_CLIENT_ID'),
        'client_secret' => env('SSO_AUTH0_CLIENT_SECRET'),
        'redirect' => '/auth/sso/auth0/callback',
    ],

    'authentik' => [
        'enabled' => env('SSO_AUTHENTIK_ENABLED', false),
        'base_url' => env('SSO_AUTHENTIK_BASE_URL'),
        'client_id' => env('SSO_AUTHENTIK_CLIENT_ID'),
        'client_secret' => env('SSO_AUTHENTIK_CLIENT_SECRET'),
        'redirect' => '/auth/sso/authentik/callback',
    ],

    'azure' => [
        'enabled' => env('SSO_AZURE_ENABLED', false),
        'client_id' => env('SSO_AZURE_CLIENT_ID'),
        'client_secret' => env('SSO_AZURE_CLIENT_SECRET'),
        'tenant' => env('SSO_AZURE_TENANT_ID'),
        'proxy' => env('PROXY'),
        'redirect' => '/auth/sso/azure/callback',
    ],

    'cognito' => [
        'enabled' => env('SSO_COGNITO_ENABLED', false),
        'host' => env('SSO_COGNITO_HOST'),
        'client_id' => env('SSO_COGNITO_CLIENT_ID'),
        'client_secret' => env('SSO_COGNITO_CLIENT_SECRET'),
        'redirect' => env('SSO_COGNITO_CALLBACK_URL'),
        'scope' => explode(',', env('SSO_COGNITO_LOGIN_SCOPE', '')),
        'logout_uri' => env('SSO_COGNITO_SIGN_OUT_URL'),
    ],

    'fusionauth' => [
        'enabled' => env('SSO_FUSIONAUTH_ENABLED', false),
        'base_url' => env('SSO_FUSIONAUTH_BASE_URL'),
        'client_id' => env('SSO_FUSIONAUTH_CLIENT_ID'),
        'client_secret' => env('SSO_FUSIONAUTH_CLIENT_SECRET'),
        'redirect' => '/auth/sso/fusionauth/callback',
    ],

    'google' => [
        'enabled' => env('SSO_GOOGLE_ENABLED', false),
        'client_id' => env('SSO_GOOGLE_CLIENT_ID'),
        'client_secret' => env('SSO_GOOGLE_CLIENT_SECRET'),
        'redirect' => '/auth/sso/{provider}/callback',
    ],

    'github' => [
        'enabled' => env('SSO_GITHUB_ENABLED', false),
        'client_id' => env('SSO_GITHUB_CLIENT_ID'),
        'client_secret' => env('SSO_GITHUB_CLIENT_SECRET'),
        'redirect' => '/auth/sso/github/callback',
    ],

    'gitlab' => [
        'enabled' => env('SSO_GITLAB_ENABLED', false),
        'host' => env('SSO_GITLAB_HOST'),
        'client_id' => env('SSO_GITLAB_CLIENT_ID'),
        'client_secret' => env('SSO_GITLAB_CLIENT_SECRET'),
        'redirect' => '/auth/sso/gitlab/callback',
    ],

    'keycloak' => [
        'enabled' => env('SSO_KEYCLOAK_ENABLED', false),
        'client_id' => env('SSO_KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('SSO_KEYCLOAK_CLIENT_SECRET'),
        'realms' => env('SSO_KEYCLOAK_REALM'),
        'redirect' => '/auth/sso/keycloak/callback',
    ],

    'oidc' => [
        'enabled' => env('SSO_OIDC_ENABLED', false),
        'base_url' => env('SSO_OIDC_BASE_URL'),
        'client_id' => env('SSO_OIDC_CLIENT_ID'),
        'client_secret' => env('SSO_OIDC_CLIENT_SECRET'),
        'scopes' => env('SSO_OIDC_SCOPES'),
        'redirect' => '/auth/sso/oidc/callback',
    ],

    'okta' => [
        'enabled' => env('SSO_OKTA_ENABLED', false),
        'base_url' => env('SSO_OKTA_BASE_URL'),
        'client_id' => env('SSO_OKTA_CLIENT_ID'),
        'client_secret' => env('SSO_OKTA_CLIENT_SECRET'),
        'redirect' => '/auth/sso/okta/callback',
    ],

    'zitadel' => [
        'enabled' => env('SSO_ZITADEL_ENABLED', false),
        'client_id' => env('SSO_ZITADEL_CLIENT_ID'),
        'client_secret' => env('SSO_ZITADEL_CLIENT_SECRET'),
        'base_url' => env('SSO_ZITADEL_BASE_URL'),
        'organization_id' => env('SSO_ZITADEL_ORGANIZATION_ID'),
        'project_id' => env('SSO_ZITADEL_PROJECT_ID'),
        'post_logout_redirect_uri' => env('SSO_ZITADEL_POST_LOGOUT_REDIRECT_URI', '/'),
        'redirect' => '/auth/sso/zitadel/callback',
    ],

];
