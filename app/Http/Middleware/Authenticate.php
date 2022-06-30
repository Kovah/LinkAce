<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as IlluminateAuthenticate;

class Authenticate extends IlluminateAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if ($request->user()->isBlocked()) {
            abort(403, trans('user.block_warning'));
        }

        return $next($request);
    }
}
