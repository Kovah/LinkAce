<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check for logged-in users
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        if (systemsettings('guest_access_enabled')) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
