<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureRateLimiting();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Build the password reset URL from the configured application URL
        // instead of the current request, as the host of an unauthenticated
        // request must never end up in a mail sent to a user
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $path = route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], absolute: false);

            return rtrim(config('app.url'), '/') . $path;
        });

        Fortify::loginView(function () {
            if (config('auth.sso.enabled') && config('auth.sso.regular_login_disabled') && config('auth.sso.auto_redirect')) {
                foreach (config('auth.sso.providers', []) as $provider) {
                    if (config('services.' . $provider . '.enabled')) {
                        return redirect()->route('auth.sso.redirect', ['provider' => $provider]);
                    }
                }
            }

            return view('auth.login', ['pageTitle' => trans('linkace.login')]);
        });

        Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.email', ['pageTitle' => trans('linkace.login')]));

        Fortify::resetPasswordView(fn() => view('auth.passwords.reset', ['pageTitle' => trans('linkace.login')]));

        Fortify::confirmPasswordView(fn() => view('auth.confirm-password', ['pageTitle' => trans('linkace.login')]));

        Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge', ['pageTitle' => trans('linkace.login')]));
    }

    /**
     * The two factor challenge must be rate limited, as the six digit codes
     * could otherwise be guessed. The limit is bound to the user attempting to
     * log in instead of their IP address, which could be spoofed.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id') ?: $request->ip());
        });
    }
}
