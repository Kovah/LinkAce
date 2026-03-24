<?php

namespace App\Http\Middleware;

use App\Actions\Settings\SetDefaultSettingsForUser;
use App\Enums\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ProxyAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('auth.proxy.enabled') !== true || !$this->canResolveUsers()) {
            return $next($request);
        }

        $email = $this->firstHeaderValue($request, config('auth.proxy.email_headers', []));
        $id = $this->firstHeaderValue($request, config('auth.proxy.id_headers', []));

        if ($email === null && $id === null) {
            $this->logoutCurrentUser($request);
            return $next($request);
        }

        $user = $this->resolveUser($email, $id);

        if ($user === null) {
            if (config('auth.proxy.auto_create_users') !== true) {
                abort(403, trans('auth.unauthorized'));
            }

            $user = $this->createUser($request, $email, $id);
        } elseif (config('auth.proxy.update_user_details') === true) {
            $this->updateUser($request, $user, $email, $id);
        }

        if (!$request->user()?->is($user)) {
            Auth::login($user, true);
            $request->session()->regenerate();
        }

        return $next($request);
    }

    protected function canResolveUsers(): bool
    {
        return Schema::hasTable('users')
            && Schema::hasTable('roles')
            && Schema::hasTable('model_has_roles');
    }

    protected function firstHeaderValue(Request $request, array $headerNames): ?string
    {
        foreach ($headerNames as $headerName) {
            $value = trim((string) $request->headers->get($headerName, ''));

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function resolveUser(?string $email, ?string $id): ?User
    {
        if ($email !== null) {
            $user = User::where('email', $email)->first();

            if ($user !== null) {
                return $user;
            }
        }

        if ($id !== null) {
            return User::where('sso_id', $id)->where('sso_provider', 'proxy')->first();
        }

        return null;
    }

    protected function createUser(Request $request, ?string $email, ?string $id): User
    {
        $user = User::create([
            'email' => $email ?? $this->generatePlaceholderEmail($id),
            'name' => $this->resolveDisplayName($request, $email, $id),
            'password' => null,
            'sso_id' => $id,
            'sso_provider' => $id !== null ? 'proxy' : null,
        ]);

        (new SetDefaultSettingsForUser($user))->up();
        $user->assignRole(Role::USER);

        return $user;
    }

    protected function updateUser(Request $request, User $user, ?string $email, ?string $id): void
    {
        $name = $this->resolveDisplayName($request, $email ?? $user->email, $id ?? $user->sso_id);
        $attributes = ['name' => $name];

        if ($user->email === null && $email !== null) {
            $attributes['email'] = $email;
        }

        if ($id !== null && $user->sso_id === null) {
            $attributes['sso_id'] = $id;
            $attributes['sso_provider'] = 'proxy';
        }

        if ($user->only(array_keys($attributes)) !== $attributes) {
            $user->update($attributes);
        }
    }

    protected function resolveDisplayName(Request $request, ?string $email, ?string $id): string
    {
        $name = $this->firstHeaderValue($request, config('auth.proxy.name_headers', []));

        if ($name !== null) {
            return mb_substr($name, 0, 20);
        }

        $fallback = $email !== null ? (strtok($email, '@') ?: $email) : ($id ?? 'proxy-user');

        return mb_substr($fallback, 0, 20);
    }

    protected function generatePlaceholderEmail(?string $id): string
    {
        $identifier = $id ?? uniqid('proxy-user-', true);

        return mb_substr($identifier, 0, 80) . '@proxy.local';
    }

    protected function logoutCurrentUser(Request $request): void
    {
        if (!Auth::check()) {
            return;
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
