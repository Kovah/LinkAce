<?php

namespace App\Helper;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class LinkAce
 *
 * @package App\Helper
 */
class LinkAce
{
    /**
     * Get the title and description of a website form it's URL
     *
     * @param string $url
     * @return array
     */
    public static function getMetaFromURL(string $url): array
    {
        $fallback = [
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => null,
        ];

        // Try to get the HTML content of that URL
        try {
            $response = Http::timeout(5)->get($url);
        } catch (ConnectionException $e) {
            flash(trans('link.added_connection_error'), 'warning');
            Log::warning($url . ': ' . $e->getMessage());

            return $fallback;
        } catch (RequestException $e) {
            flash(trans('link.added_request_error'), 'warning');
            Log::warning($url . ': ' . $e->getMessage());

            return $fallback;
        }

        if (!$response->successful()) {
            return $fallback;
        }

        $html = $response->body();

        if (empty($html)) {
            return $fallback;
        }

        // Try to get the meta tags of that URL
        try {
            $tags = get_meta_tags($url);
        } catch (\Exception $e) {
            return $fallback;
        }

        // Parse the HTML for the title
        $res = preg_match("/<title>(.*)<\/title>/siU", $html, $title_matches);

        if ($res) {
            // Clean up title: remove EOL's and excessive whitespace.
            $title = preg_replace('/\s+/', ' ', $title_matches[1]);
            $title = trim($title);
        }

        // Get the title or the og:description tag or the twitter:description tag
        $description = $tags['description']
            ?? $tags['og:description']
            ?? $tags['twitter:description']
            ?? $fallback['description'];

        return [
            'title' => $title ?? $fallback['title'],
            'description' => $description,
        ];
    }

    /**
     * Generate the code for the bookmarklet
     */
    public static function generateBookmarkletCode(): string
    {
        $bm_code = 'javascript:javascript:(function(){var%20url%20=%20location.href;' .
            "var%20title%20=%20document.title%20||%20url;window.open('##URL##?u='%20+%20encodeURIComponent(url)" .
            "+'&t='%20+%20encodeURIComponent(title),'_blank','menubar=no,height=720,width=600,toolbar=no," .
            "scrollbars=yes,status=no,dialog=1');})();";

        $bm_code = str_replace('##URL##', route('bookmarklet-add'), $bm_code);

        return $bm_code;
    }
}
