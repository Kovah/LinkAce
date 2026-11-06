<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiHeaderValidationMiddleware
{
    /**
     * Validate API headers for JSON requests.
     *
     * POST, PUT, PATCH and DELETE requests must send JSON input.
     * Accept header must be present and allow application/json for response content negotiation only.
     * This middleware enforces strict adherence to API standards for content negotiation and media type validation.
     * It ensures that clients correctly specify their expectations for both request payloads and response formats,
     * improving interoperability and robustness of the API.
     */

    /**
     * Supported response media types for content negotiation.
     * According to RFC 9110, the server selects a representation
     * based on the Accept header.
     */
    private const SUPPORTED_RESPONSE_TYPES = [
        'application/json',
    ];

    /**
     * Supported request media types for incoming payloads.
     * Based on RFC 8259 (JSON).
     */
    private const SUPPORTED_REQUEST_TYPES = [
        'application/json',
    ];

    /**
     * HTTP methods that are expected to contain a request body.
     */
    private const METHODS_WITH_BODY = [
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
    ];

    /**
     * Handle an incoming request and validate API headers.
     *
     * This middleware enforces:
     * - Content negotiation via the Accept header (RFC 9110)
     * - Validation of Content-Type for request bodies (RFC 9110 / RFC 8259)
     *
     * Returns:
     * - 406 Not Acceptable if response media type cannot be negotiated
     * - 415 Unsupported Media Type if request body format is invalid
     *
     * @param Request $request Incoming HTTP request
     * @param Closure $next Next middleware
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isApiRequest($request)) {
            return $next($request);
        }

        // 1. CONTENT NEGOTIATION (Accept Header)
        $negotiated = $this->negotiateResponseType($request);

        if ($negotiated === null) {
            return $this->errorResponse(
                'No acceptable response format found.',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        // 2. REQUEST BODY VALIDATION (Content-Type)
        if ($this->requiresBody($request)) {
            $contentType = $this->parseMediaType(
                $request->header('Content-Type')
            );

            if ($contentType === null || !$this->isSupportedRequestType($contentType)) {
                return $this->errorResponse(
                    'Unsupported Content-Type. Expected application/json.',
                    Response::HTTP_UNSUPPORTED_MEDIA_TYPE
                );
            }
        }

        return $next($request);
    }

    // --------------------------------------------------
    // Request Classification
    // --------------------------------------------------

    /**
     * Determine whether the request targets the API.
     *
     * This implementation assumes all API routes are prefixed with "api/".
     *
     * @param Request $request
     * @return bool
     */
    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->path(), 'api/');
    }

    /**
     * Check if the current HTTP method requires a request body.
     *
     * @param Request $request
     * @return bool
     */
    private function requiresBody(Request $request): bool
    {
        return in_array($request->method(), self::METHODS_WITH_BODY, true);
    }

    // --------------------------------------------------
    // Content Negotiation Engine (RFC 9110)
    // --------------------------------------------------

    /**
     * Perform content negotiation based on the Accept header.
     *
     * Implements RFC 9110:
     * - Supports multiple media types
     * - Supports quality values (q=)
     * - Response media types are fixed to application/json for this API
     *
     * @param Request $request
     * @return string|null Negotiated media type or null if none matches
     */
    private function negotiateResponseType(Request $request): ?string
    {
        $acceptHeader = $request->header('Accept');

        // RFC: missing Accept
        if ($acceptHeader === null) {
            return null;
        }

        $acceptedTypes = $this->parseAcceptHeader($acceptHeader);

        foreach ($acceptedTypes as $accepted) {
            foreach (self::SUPPORTED_RESPONSE_TYPES as $supported) {
                if ($this->mediaTypeMatches($accepted['type'], $supported)) {
                    return $supported;
                }
            }
        }

        return null;
    }

    /**
     * Parse the Accept header into structured media types.
     *
     * Extracts:
     * - media type
     * - quality value (q-factor)
     *
     * Result is sorted by highest priority first.
     *
     * @param string $header
     * @return array
     */
    private function parseAcceptHeader(string $header): array
    {
        $result = [];

        $parts = explode(',', $header);

        foreach ($parts as $part) {
            $subParts = explode(';', trim($part));

            $mediaType = trim(array_shift($subParts));
            if ($mediaType === '') {
                continue;
            }

            $q = 1.0;

            foreach ($subParts as $param) {
                $param = trim($param);
                if (str_starts_with($param, 'q=')) {
                    $rawQ = trim(substr($param, 2));
                    if (is_numeric($rawQ)) {
                        $q = max(0.0, min(1.0, (float) $rawQ));
                    } else {
                        $q = 0.0;
                    }
                }
            }
            if ($q <= 0.0) {
                continue;
            }

            $result[] = [
                'type' => strtolower($mediaType),
                'q' => $q,
            ];
        }

        // Sort descending by quality (RFC compliant)
        usort($result, fn($a, $b) => $b['q'] <=> $a['q']);

        return $result;
    }

    /**
     * Match accepted media type against supported type.
     *
     * Supports:
     * - Exact matches (application/json)
     *
     * @param string $accepted
     * @param string $supported
     * @return bool
     */
    private function mediaTypeMatches(string $accepted, string $supported): bool
    {
        if ($accepted === $supported) {
            return true;
        }

        return false;
    }

    // --------------------------------------------------
    // Content-Type Handling
    // --------------------------------------------------

    /**
     * Parse the Content-Type header and extract the base media type.
     *
     * Removes parameters like charset (e.g. "application/json; charset=utf-8").
     *
     * @param string|null $header
     * @return string|null
     */
    private function parseMediaType(?string $header): ?string
    {
        if ($header === null) {
            return null;
        }

        $parts = explode(';', strtolower($header));

        return trim($parts[0]);
    }

    /**
     * Check if the given media type is supported.
     *
     * @param string $type
     * @return bool
     */
    private function isSupportedRequestType(string $type): bool
    {
        return in_array($type, self::SUPPORTED_REQUEST_TYPES, true);
    }

    // --------------------------------------------------
    // Error Handling (RFC 7807-like)
    // --------------------------------------------------

    /**
     * Generate a standardized JSON error response.
     *
     * Structure is aligned with RFC 7807 (Problem Details),
     * but simplified.
     *
     * @param string $message
     * @param int $status
     * @return Response
     */
    private function errorResponse(string $message, int $status): Response
    {
        return response()->json([
            'type' => 'about:blank',
            'title' => Response::$statusTexts[$status] ?? 'Error',
            'status' => $status,
            'detail' => $message,
        ], $status);
    }
}
