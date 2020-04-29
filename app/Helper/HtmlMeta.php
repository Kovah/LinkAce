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
     * @param $url
     * @return array
     */
    public static function getFromUrl($url): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'title' => $url,
                'description' => null,
            ];
        }

        $fallback = [
            'success' => false,
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => null,
        ];

        $html = self::getHtmlContent($url);

        if ($html === null) {
            return $fallback;
        }

        $title = self::parseTitle($html);
        $metaTags = self::getMetaTags($html);

        $description = $metaTags['description']
            ?? $metaTags['og:description']
            ?? $metaTags['twitter:description']
            ?? $fallback['description'];

        return [
            'success' => true,
            'title' => $title ?? $fallback['title'],
            'description' => $description,
        ];
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
            flash(trans('link.added_connection_error'), 'warning');
            Log::warning($url . ': ' . $e->getMessage());

            return null;
        } catch (RequestException $e) {
            flash(trans('link.added_request_error'), 'warning');
            Log::warning($url . ': ' . $e->getMessage());

            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        return $response->body();
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
        $pattern = '/<[\s]*meta[\s]*(name|property)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';

        if (preg_match_all($pattern, $html, $out)) {
            return array_combine($out[2], $out[3]);
        }

        return [];
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
