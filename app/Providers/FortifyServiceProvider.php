<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        Fortify::authenticateUsing(function (Request $request) {
            if (!databaseLoginEnabled()) {
                abort(403, trans('auth.login_disabled'));
            }

            $user = User::where('email', $request->email)->first();

            if ($user !== null && $user->password !== null && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        Fortify::loginView(function () {
            if (authProxyEnabled()) {
                abort(403, trans('auth.proxy_missing_identity'));
            }

            return view('auth.login', ['pageTitle' => trans('linkace.login')]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            if (!databaseLoginEnabled()) {
                abort(403, trans('auth.login_disabled'));
            }

            return view('auth.passwords.email', ['pageTitle' => trans('linkace.login')]);
        });

        Fortify::resetPasswordView(function () {
            if (!databaseLoginEnabled()) {
                abort(403, trans('auth.login_disabled'));
            }

            return view('auth.passwords.reset', ['pageTitle' => trans('linkace.login')]);
        });

        Fortify::confirmPasswordView(fn() => view('auth.confirm-password', ['pageTitle' => trans('linkace.login')]));

        Fortify::twoFactorChallengeView(function () {
            if (!databaseLoginEnabled()) {
                abort(403, trans('auth.login_disabled'));
            }

            return view('auth.two-factor-challenge', ['pageTitle' => trans('linkace.login')]);
        });
    }
}
