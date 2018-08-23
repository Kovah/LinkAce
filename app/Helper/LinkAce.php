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
     * @return null|string|string[]
     */
    public static function getTitleFromURL(string $url)
    {
        $fp = file_get_contents($url);

        if (!$fp) {
            return null;
        }

        $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);

        if (!$res) {
            return null;
        }

        // Clean up title: remove EOL's and excessive whitespace.
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);
        $title = trim($title);

        return $title;
    }
}
