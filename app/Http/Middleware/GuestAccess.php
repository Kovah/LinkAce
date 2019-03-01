<?php

namespace App\Http\Middleware;

use Closure;

class GuestAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check for logged in users
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        // Check if guest access is enabled
        if (systemsettings('system_guest_access') === '1') {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
