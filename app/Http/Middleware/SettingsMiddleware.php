<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class SettingsMiddleware
 *
 * @package App\Http\Middleware
 */
class SettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Set global configs based on the user settings
        if ($user_timezone = usersettings('timezone')) {
            config(['app.timezone' => $user_timezone]);
        }

        return $next($request);
    }
}
