<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Session::pull('bookmarklet.login_redirect')) {
                return redirect()->route('bookmarklet-add', [
                    'u' => session('bookmarklet.new_url'),
                    't' => session('bookmarklet.new_title'),
                ]);
            }

            return redirect('/dashboard');
        }

        return $next($request);
    }
}
