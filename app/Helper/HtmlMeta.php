<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use Kovah\HtmlMeta\Exceptions\InvalidUrlException;
use Kovah\HtmlMeta\Exceptions\UnreachableUrlException;

class HtmlMeta
{
    /** @var array */
    protected static $fallback;

    /**
     * Get the title and description of an URL.
     *
     * Returned array:
     * array [
     *   'success' => bool,
     *   'title' => string,
     *   'description' => string|null,
     * ]
     *
     * @param string $url
     * @param bool   $flashAlerts
     * @return array
     */
    public static function getFromUrl(string $url, bool $flashAlerts = false): array
    {
        self::buildFallback($url);

        try {
            $meta = \Kovah\HtmlMeta\Facades\HtmlMeta::forUrl($url);
        } catch (InvalidUrlException $e) {
            Log::warning($url . ': ' . $e->getMessage());
            if ($flashAlerts) {
                flash(trans('link.added_connection_error'), 'warning');
            }
            return self::$fallback;
        } catch (UnreachableUrlException $e) {
            Log::warning($url . ': ' . $e->getMessage());
            if ($flashAlerts) {
                flash(trans('link.added_request_error'), 'warning');
            }
            return self::$fallback;
        }

        return self::buildLinkMeta($meta);
    }

    /**
     * Build a response array containing the link meta including a success flag.
     *
     * @param array $metaTags
     * @return array
     */
    protected static function buildLinkMeta(array $metaTags): array
    {
        $metaTags['description'] = $metaTags['description']
            ?? $metaTags['og:description']
            ?? $metaTags['twitter:description']
            ?? null;

        return [
            'success' => true,
            'title' => $metaTags['title'] ?? self::$fallback['title'],
            'description' => $metaTags['description'],
        ];
    }

    /**
     * The fallback is used in case of errors while trying to get the link meta.
     *
     * @param string $url
     */
    protected static function buildFallback(string $url): void
    {
        self::$fallback = [
            'success' => false,
            'title' => parse_url($url, PHP_URL_HOST) ?? $url,
            'description' => false,
        ];
    }
}
