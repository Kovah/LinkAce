<?php

namespace App\Providers;

use App\Listeners\SavingSettingsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Spatie\LaravelSettings\Events\SavingSettings;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        SavingSettings::class => [
            SavingSettingsListener::class,
        ],
        SocialiteWasCalled::class => [
            \SocialiteProviders\Auth0\Auth0ExtendSocialite::class . '@handle',
            \SocialiteProviders\Authentik\AuthentikExtendSocialite::class . '@handle',
            \SocialiteProviders\Azure\AzureExtendSocialite::class . '@handle',
            \SocialiteProviders\Cognito\CognitoExtendSocialite::class . '@handle',
            \SocialiteProviders\FusionAuth\FusionAuthExtendSocialite::class . '@handle',
            \SocialiteProviders\Keycloak\KeycloakExtendSocialite::class . '@handle',
            \SocialiteProviders\Okta\OktaExtendSocialite::class . '@handle',
            //\SocialiteProviders\Zitadel\ZitadelExtendSocialite::class . '@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
