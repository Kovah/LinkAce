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
        $fallback = [
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => null,
        ];

        // Try to get the HTML content of that URL
        try {
            $html = file_get_contents($url);
        } catch (\Exception $e) {
            return $fallback;
        }

        // Try to get the meta tags of that URL
        try {
            $tags = get_meta_tags($url);
        } catch (\Exception $e) {
            return $fallback;
        }

        if (empty($html)) {
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
