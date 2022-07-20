<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestAccess
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        if (systemsettings('guest_access_enabled')) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
