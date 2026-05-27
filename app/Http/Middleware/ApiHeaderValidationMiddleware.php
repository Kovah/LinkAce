<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiHeaderValidationMiddleware
{
    /**
     * Validate API headers for JSON requests.
     *
     * POST, PATCH and DELETE requests must send JSON input. If an Accept header is
     * provided, it must allow application/json or a wildcard.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */

    private const CONTENT_TYPE_JSON = 'application/json';

    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->isSafeMethod($request->method())) {
            return $next($request);
        }

        if (! $this->hasJsonContentType($request)) {
            return response()->json([
                'error' => 'Invalid Content-Type header, LinkAce only supports JSON input',
            ], 415);
        }

        if (! $this->acceptsJson($request)) {
            return response()->json([
                'error' => 'Invalid Accept header, LinkAce only supports JSON output',
            ], 415);
        }

        return $next($request);
    }

    protected function isSafeMethod(string $method): bool
    {
        return in_array($method, ['GET', 'HEAD', 'OPTIONS'], true);
    }

    protected function hasJsonContentType(Request $request): bool
    {
        return str_contains(strtolower($request->header('Content-Type', '')), self::CONTENT_TYPE_JSON);
    }

    protected function acceptsJson(Request $request): bool
    {
        $accept = strtolower($request->header('Accept', ''));
        return $accept === '' || str_contains($accept, self::CONTENT_TYPE_JSON) || str_contains($accept, '*/*');
    }
}
