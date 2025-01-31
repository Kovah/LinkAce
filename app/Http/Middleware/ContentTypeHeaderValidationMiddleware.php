<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentTypeHeaderValidationMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware ensures that the Content-Type header is set to
     * application/json, otherwise it will return a 415 Unsupported
     * Media Type response.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->method() === "POST" && $request->header('Content-Type') !== 'application/json') {
            return response()->json([
                'error' => 'Invalid Content-Type'
            ], 415);
        }

        return $next($request);
    }
}
