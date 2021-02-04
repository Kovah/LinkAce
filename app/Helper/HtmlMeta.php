<?php

namespace App\Helper;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class HtmlMeta
 *
 * @package App\Helper
 */
class HtmlMeta
{
    /** @var array */
    protected static $fallback;

    /** @var bool */
    protected static $flashAlerts;

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
        self::$flashAlerts = $flashAlerts;

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'title' => $url,
                'description' => null,
            ];
        }

        self::$fallback = [
            'success' => false,
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => null,
        ];

        $html = self::getHtmlContent($url);

        if ($html === null) {
            return self::$fallback;
        }

        return self::buildHtmlMeta($html);
    }

    /**
     * Try to get the HTML content of an URL.
     * If a connection or response error occurs, null is returned, otherwise
     * the HTML as a string.
     *
     * @param string $url
     * @return string|null
     */
    protected static function getHtmlContent(string $url): ?string
    {
        try {
            $response = Http::timeout(5)->get($url);
        } catch (ConnectionException $e) {
            if (self::$flashAlerts) {
                flash(trans('link.added_connection_error'), 'warning');
            }

            Log::warning($url . ': ' . $e->getMessage());

            return null;
        } catch (RequestException $e) {
            if (self::$flashAlerts) {
                flash(trans('link.added_request_error'), 'warning');
            }

            Log::warning($url . ': ' . $e->getMessage());

            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        return $response->body();
    }

    /**
     * Returns an array containing the title and description parsed from the
     * given HTML.
     *
     * If a charset meta tag was found and it does not contain UTF-8 as a value,
     * the method tries to convert both values from the given charset into UTF-8.
     * If it fails, it returns null because we most likely can't generate any
     * useful information here.
     *
     * If no charset is available, the method will check if the title is encoded
     * as UTF-8. If it does not pass the check, title and description will be set
     * to null as we will most likely not be able to get any correctly encoded
     * information from the strings without proper encoding information.
     *
     * @param string $html
     * @return array
     */
    protected static function buildHtmlMeta(string $html): array
    {
        $title = self::parseTitle($html);
        $metaTags = self::getMetaTags($html);

        $description = $metaTags['description']
            ?? $metaTags['og:description']
            ?? $metaTags['twitter:description']
            ?? self::$fallback['description'];

        if (isset($metaTags['charset']) && strtolower($metaTags['charset']) !== 'utf-8') {
            try {
                $title = iconv($metaTags['charset'], 'UTF-8', $title) ?: null;
                $description = iconv($metaTags['charset'], 'UTF-8', $description) ?: null;
            } catch (\ErrorException $e) {
                $title = null;
                $description = null;
            }
        } elseif (mb_detect_encoding($title, 'UTF-8', true) === false) {
            $title = null;
            $description = null;
        }

        return [
            'success' => true,
            'title' => $title ?? self::$fallback['title'],
            'description' => $description,
        ];
    }

    /**
     * Parses the meta tags from HTML by using a specific regex.
     * Returns an array of all found meta tags or an empty array if no tags were found.
     *
     * @param string $html
     * @return array
     */
    protected static function getMetaTags(string $html): array
    {
        $tags = [];
        $pattern = '/<[\s]*meta[\s]*(name|property)="?([^>"]*)"?[\s]*content="?([^>"]*)"?[\s]*[\/]?[\s]*>/i';

        if (preg_match_all($pattern, $html, $out)) {
            $tags = array_combine($out[2], $out[3]);
        }

        $pattern = '/<[\s]*meta[\s]*(charset)="?([^>"]*)"?[\s]*>/i';

        if (preg_match($pattern, $html, $out)) {
            $tags['charset'] = $out[2];
        }

        return $tags;
    }

    /**
     * Try to parse the title tag from the HTML by using regex.
     * If a title tag was found, excessive whitespace and newlines are removed from the string.
     *
     * @param $html
     * @return string|null
     */
    protected static function parseTitle($html): ?string
    {
        $res = preg_match("/<title>(.*)<\/title>/siU", $html, $titleMatches);

        if ($res) {
            $title = preg_replace('/\s+/', ' ', $titleMatches[1]);
            $title = trim($title);
        }

        return $title ?? null;
    }
}
