<?php

namespace App\Http\Controllers;

use App\Actions\Settings\SetDefaultSettingsForUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        $this->authorizeOauthRequest($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $this->authorizeOauthRequest($provider);

        $authUser = Socialite::driver($provider)->user();

        if (config('auth.sso.require_verified_email') === true && !$this->emailWasVerified($authUser)) {
            abort(403, trans('auth.sso_email_unverified'));
        }

        // Accounts are matched on the stable subject issued by the provider, never on
        // the email address alone, so an identity that is already linked can not be
        // replaced by a different one presenting the same email address.
        $user = User::where(['sso_provider' => $provider, 'sso_id' => $authUser->getId()])->first();

        if ($user !== null) {
            $user->update([
                'name' => $this->resolveName($authUser),
                'sso_token' => $authUser->token ?? null,
                'sso_token_secret' => $authUser->tokenSecret ?? null,
                'sso_refresh_token' => $authUser->refreshToken ?? null,
            ]);
        } elseif ($existingUser = User::where('email', $authUser->getEmail())->first()) {
            // An account with this email address exists but is not linked to this
            // identity yet. Linking is only allowed while the account does not use
            // SSO at all, which keeps the migration path for password accounts open.
            if ($existingUser->sso_provider !== null && $existingUser->sso_provider !== $provider) {
                abort(403, trans('auth.sso_wrong_provider', [
                    'currentProvider' => trans('auth.sso_provider.' . $provider),
                    'userProvider' => trans('auth.sso_provider.' . $existingUser->sso_provider),
                ]));
            }

            if ($existingUser->sso_provider === $provider) {
                abort(403, trans('auth.sso_account_already_linked'));
            }

            $user = $existingUser;
            $user->update([
                'name' => $this->resolveName($authUser),
                'sso_id' => $authUser->getId(),
                'sso_provider' => $provider,
                'sso_token' => $authUser->token ?? null,
                'sso_token_secret' => $authUser->tokenSecret ?? null,
                'sso_refresh_token' => $authUser->refreshToken ?? null,
            ]);
        } elseif (config('auth.sso.registration_enabled') === false) {
            // Users should not be able to register new accounts on their own
            abort(403, trans('auth.sso_registration_disabled'));
        } else {
            $user = User::create([
                'name' => $this->resolveName($authUser),
                'email' => $authUser->getEmail(),
                'sso_id' => $authUser->getId(),
                'sso_provider' => $provider,
                'sso_token' => $authUser->token ?? null,
                'sso_token_secret' => $authUser->tokenSecret ?? null,
                'sso_refresh_token' => $authUser->refreshToken ?? null,
            ]);

            (new SetDefaultSettingsForUser($user))->up();
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    protected function resolveName(SocialiteUser $authUser): string
    {
        return $authUser->getNickname()
            ?: (($authUser->getRaw() ?? [])['preferred_username'] ?? Str::studly($authUser->getName()));
    }

    /**
     * Whether the provider asserted that it verified the email address it supplied.
     * The claim is a standard OIDC claim of the `email` scope, but providers are not
     * required to return it, and some send it as a string instead of a boolean.
     *
     * @param SocialiteUser $authUser
     * @return bool
     */
    protected function emailWasVerified(SocialiteUser $authUser): bool
    {
        $claim = ($authUser->getRaw() ?? [])['email_verified'] ?? null;

        return $claim === true || $claim === 'true' || $claim === 1 || $claim === '1';
    }

    protected function authorizeOauthRequest(string $provider): void
    {
        if (config('auth.sso.enabled') !== true || !in_array($provider, config('auth.sso.providers'))) {
            abort(403, trans('auth.unauthorized'));
        }

        if (config('services.' . $provider . '.enabled') !== true) {
            abort(403, trans('auth.sso_provider_disabled'));
        }
    }
}
