<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as IlluminateAuthenticate;

class Authenticate extends IlluminateAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->has('api_token') && !$request->headers->has('Authorization')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->api_token);
        }

        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException $exception) {
            if (config('auth.proxy.enabled') === true && !$request->is('api/*')) {
                abort(403, trans('auth.proxy_missing_identity'));
            }

            throw $exception;
        }

        if (!$request->is('api/*') && $request->user()->isSystemUser()) {
            abort(403, trans('user.system_user_locked'));
        }

        if ($request->user()->isBlocked()) {
            abort(403, trans('user.block_warning'));
        }

        return $next($request);
    }
}
