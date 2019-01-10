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
     * Get the title of an HTML page b
     *
     * @param string $url
     * @return string|string[]
     */
    public static function getTitleFromURL(string $url)
    {
        $fail_return = parse_url($url, PHP_URL_HOST);

        try {
            $fp = file_get_contents($url);
        } catch (\Exception $e) {
            return $fail_return;
        }

        if (!$fp) {
            return $fail_return;
        }

        $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);

        if (!$res) {
            return $fail_return;
        }

        // Clean up title: remove EOL's and excessive whitespace.
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);
        $title = trim($title);

        return $title;
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
