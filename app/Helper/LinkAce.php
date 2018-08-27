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
}
