<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetupCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('setup/*')) {
            if (config('app.setup_completed') === true) {
                // Do not allow access to setup after it was completed
                return redirect()->route('front');
            }

            // Skip check if current route targets the setup
            return $next($request);
        }

        if (config('app.setup_completed') !== true) {
            // Start the setup if it was not completed yet
            return redirect()->route('setup.welcome');
        }

        return $next($request);
    }
}
