<?php

namespace App\Helper;

/**
 * Class LinkIconMapper
 *
 * @package App\Helper
 */
class LinkIconMapper
{
    /** @var string Default icon as fallback if no specific icon was found */
    public static $default_icon = 'fa-link';

    /** @var array Array holding the pattern => icon map, sorted alphabetically */
    public static $icon_map = [
        '500px.com' => 'fab-500px',
        'adobe.com' => 'fab-adobe',
        'aws.amazon.com' => 'fab-aws', // put before amazon pattern to keep it easy
        'amazon.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab-amazon', // match amazon with every TLD
        'angel.co' => 'fab-angellist',
        'itunes.apple.com\/app' => 'fab-app-store',
        'apple.com' => 'fab-apple',
        'artstation.com' => 'fab-artstation',
        'atlassian.(com|net)' => 'fab-atlassian',
        'bandcamp.com' => 'fab-bandcamp',
        'behance.net' => 'fab-behance',
        'bitbucket.org' => 'fab-bitbucket',
        'blockchain.com' => 'fab-bitcoin',
        'blogger.com' => 'fab-blogger-b',
        'chrome.google.com\/webstore' => 'fab-chrome',
        'codepen.io' => 'fab-codepen',
        'dev.to' => 'fab-dev',
        'deviantart.com' => 'fab-deviantart',
        'digitalocean.com' => 'fab-digital-ocean',
        '(discordapp.com|discord.gg)' => 'fab-discord',
        'meta.discourse.org' => 'fab-discourse',
        'docker.com' => 'fab-docker',
        'dribbble.com' => 'fab-dribbble',
        'dropbox.com' => 'fab-dropbox',
        'drupal.org' => 'fab-drupal',
        'ebay.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab-ebay',
        'ello.co' => 'fab-ello',
        'etsy.com' => 'fab-etsy',
        '(facebook.com|fb.me)' => 'fab-facebook-f',
        'addons.mozilla.org\/[a-zA-Z-_]+\/firefox' => 'fab-firefox',
        'figma.com' => 'fab-figma',
        'flickr.com' => 'fab-flickr',
        '(flipboard.com|flip.it)' => 'fab-flipboard',
    ];

    public static function mapIcon($url): string
    {
        // Check if the URL is valid
        if (!$domain = self::getDomain($url)) {
            return self::$default_icon;
        }

        foreach (self::$icon_map as $pattern => $icon) {
            if (preg_match('/' . $pattern . '/i', $url)) {
                return $icon;
            }
        }

        return self::$default_icon;
    }

    /**
     * @param $url
     * @return string|null
     */
    public static function getDomain($url)
    {
        $details = parse_url($url);

        return $details['host'] ?? null;
    }
}
