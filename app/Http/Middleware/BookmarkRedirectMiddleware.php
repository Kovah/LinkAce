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
        if ($request->is('dashboard') && session()->pull('bookmarklet.login_redirect', false)) {
            return redirect()->route('bookmarklet-add', [
                'u' => session()->pull('bookmarklet.new_url'),
                't' => session()->pull('bookmarklet.new_title'),
                'tags' => session()->pull('bookmarklet.new_tags'),
                'lists' => session()->pull('bookmarklet.new_lists'),
            ]);
        }

        return $next($request);
    }
}
