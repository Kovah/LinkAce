<?php

namespace App\Helper;

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
     * @return string|string[]
     */
    public static function getMetaFromURL(string $url)
    {
        $title_fallback = parse_url($url, PHP_URL_HOST);

        $fallback = [
            'title' => $title_fallback,
            'description' => null,
        ];

        // Try to get the HTML content of that URL
        try {
            $html = file_get_contents($url);
        } catch (\Exception $e) {
            return $fallback;
        }

        if (!$html) {
            return $fallback;
        }

        // Parse the HTML for the title
        $res = preg_match("/<title>(.*)<\/title>/siU", $html, $title_matches);

        if ($res) {
            // Clean up title: remove EOL's and excessive whitespace.
            $title = preg_replace('/\s+/', ' ', $title_matches[1]);
            $title = trim($title);
        }

        // Parse the HTML for the meta description, or alternatively for the og:description property
        $res = preg_match(
            '/<meta (?:property="og:description"|name="description") content="(.*?)"(?:\s\/)?>/i',
            $html,
            $description_matches
        );

        if ($res) {
            // Clean up description: remove EOL's and excessive whitespace.
            $description = preg_replace('/\s+/', ' ', $description_matches[1]);
            $description = trim($description);
        }

        return [
            'title' => $title ?? $title_fallback,
            'description' => $description ?? null,
        ];
    }

    /**
     * Generate the code for the bookmarklet
     *
     * @return mixed|string
     */
    public static function generateBookmarkletCode()
    {
        $bm_code = "javascript:javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('##URL##?u='%20+%20encodeURIComponent(url)+'&t='%20+%20encodeURIComponent(title),'_blank','menubar=no,height=720,width=600,toolbar=no,scrollbars=yes,status=no,dialog=1');})();";

        $bm_code = str_replace('##URL##', route('bookmarklet-add'), $bm_code);

        return $bm_code;
    }
}
