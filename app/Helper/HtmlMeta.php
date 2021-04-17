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

        return self::buildLinkMeta($meta, $url);
    }

    /**
     * Build a response array containing the link meta including a success flag.
     *
     * @param array $metaTags
     * @param string $url
     * @return array
     */
    protected static function buildLinkMeta(array $metaTags, string $url): array
    {
        $metaTags['description'] = $metaTags['description']
            ?? $metaTags['og:description']
            ?? $metaTags['twitter:description']
            ?? null;

        $thumbnail = $metaTags['og:image']
            ?? $metaTags['twitter:image']
            ?? null;

        //Edge case of Youtube only (because of Youtube EU cookie consent)
        if (str_contains($url, 'youtube')
            && str_contains($url, 'v=')
            && is_null($thumbnail)
        ) {
            //Formula based on https://stackoverflow.com/a/2068371
            $explode = explode('v=', $url);
            //https://img.youtube.com/vi/[video-id]/mqdefault.jpg
            $thumbnail = 'https://img.youtube.com/vi/' . $explode[1] . '/mqdefault.jpg';
        }

        return [
            'success' => true,
            'title' => $metaTags['title'] ?? self::$fallback['title'],
            'description' => $metaTags['description'],
            'thumbnail' => $thumbnail,
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
            'thumbnail' => null,
        ];
    }
}
