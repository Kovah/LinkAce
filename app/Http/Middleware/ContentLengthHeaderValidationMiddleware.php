<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContentLengthHeaderValidationMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware ensures that the Content-Length header is set,
     * otherwise it will return a 411 Length Required response.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->method() === "POST" && $request->header('Content-Length') === null) {
            return response()->json([
                'error' => 'Content-Length header is required.'
            ], 411);
        }

        return $next($request);
    }
}
