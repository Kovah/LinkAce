<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookmarkRedirectMiddleware
{
    /**
     * Handle an incoming request.
     * Rather hacky way to do this. Will be kept until Fortify offers callbacks for authenticated redirects.
     * See https://github.com/laravel/fortify/issues/77
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::get('bookmarklet.login_redirect', false) && $request->is('dashboard')) {
            Session::forget('bookmarklet.login_redirect');

            return redirect()->route('bookmarklet-add', [
                'u' => session('bookmarklet.new_url'),
                't' => session('bookmarklet.new_title'),
            ]);
        }

        return $next($request);
    }
}
