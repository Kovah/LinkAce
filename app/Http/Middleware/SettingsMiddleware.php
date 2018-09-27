<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;

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
        // Get all system settings
        $sys_settings = Setting::systemOnly()
            ->get()
            ->except(['id', 'user_id']);

        view()->share('system_settings', $sys_settings);

        // Get all user settings
        $user_settings = auth()->check()
            ? Setting::byUser(auth()->user()->id)
                ->get()
                ->except(['id', 'user_id'])
            : [];

        view()->share('user_settings', $user_settings);

        return $next($request);
    }
}
