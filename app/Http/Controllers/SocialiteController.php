<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        $this->authorizeOauthRequest($provider);

        return Socialite::driver($provider)->redirect()->setTargetUrl(
            route('auth.oauth.callback', ['provider' => $provider])
        );
    }

    public function callback(string $provider)
    {
        $this->authorizeOauthRequest($provider);

        $authUser = Socialite::driver($provider)->user();

        // If a user with the provided email address already exists, register the oauth login
        // @TODO what about users who try to login to a different OAuth provider?
        if (User::where('email', $authUser->getEmail())->exists()) {
            $user = User::where('email', $authUser->getEmail())->first();
            $user->update([
                'oauth_id' => $authUser->id,
                'oauth_provider' => $provider,
                'oauth_token' => $authUser->token ?? null,
                'oauth_token_secret' => $authUser->tokenSecret ?? null,
                'oauth_refresh_token' => $authUser->refreshToken ?? null,
            ]);
        } else {
            // otherwise, either update an existing oauth user or register a new user
            $user = User::updateOrCreate([
                'email' => $authUser->getEmail(),
                'oauth_id' => $authUser->getId(),
                'oauth_provider' => $provider,
            ], [
                'name' => $authUser->getNickname(),
                'email' => $authUser->getEmail(),
                'oauth_id' => $authUser->getId(),
                'oauth_provider' => $provider,
                'oauth_token' => $authUser->token ?? null,
                'oauth_token_secret' => $authUser->tokenSecret ?? null,
                'oauth_refresh_token' => $authUser->refreshToken ?? null,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    protected function authorizeOauthRequest(string $provider): void
    {
        if (config('auth.oauth.enabled') !== true || !in_array($provider, config('auth.oauth.providers'))) {
            abort(403, 'Login unauthorized');
        }
    }
}
