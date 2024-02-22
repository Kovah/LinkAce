<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(fn() => view('auth.login', ['pageTitle' => trans('linkace.login')]));

        Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.email', ['pageTitle' => trans('linkace.login')]));

        Fortify::resetPasswordView(fn() => view('auth.passwords.reset', ['pageTitle' => trans('linkace.login')]));

        Fortify::confirmPasswordView(fn() => view('auth.confirm-password', ['pageTitle' => trans('linkace.login')]));

        Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge', ['pageTitle' => trans('linkace.login')]));
    }
}
