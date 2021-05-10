<?php

namespace App\Helper;

class LinkIconMapper
{
    /** @var string Default icon as fallback if no specific icon was found */
    public static $defaultIcon = 'link';

    /** @var array Array holding the pattern => icon map, sorted alphabetically */
    public static $iconMap = [
        '500px\.com' => 'brand.500px',
        'amazon\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'brand.amazon',
        'itunes\.apple\.com\/app' => 'brand.app-store-ios',
        'apple\.com' => 'brand.apple',
        'artstation\.com' => 'brand.artstation',
        'atlassian\.(com|net)' => 'brand.atlassian',
        'bandcamp\.com' => 'brand.bandcamp',
        'behance\.net' => 'brand.behance',
        'bitbucket\.org' => 'brand.bitbucket',
        'blockchain\.com' => 'brand.bitcoin',
        'blogger\.com' => 'brand.blogger-b',
        'chrome\.google\.com\/webstore' => 'brand.chrome',
        'codepen\.io' => 'brand.codepen',
        'dev\.to' => 'brand.dev',
        'deviantart\.com' => 'brand.deviantart',
        '(discordapp\.com|discord\.gg)' => 'brand.discord',
        'docker\.com' => 'brand.docker',
        'dribbble\.com' => 'brand.dribbble',
        'dropbox\.com' => 'brand.dropbox',
        'ebay\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'brand.ebay',
        'etsy\.com' => 'brand.etsy',
        '(facebook\.com|fb\.me)' => 'brand.facebook',
        'addons\.mozilla\.org\/[a-zA-Z-_]+\/firefox' => 'brand.firefox',
        'flickr\.com' => 'brand.flickr',
        '(flipboard\.com|flip\.it)' => 'brand.flipboard',
        '(getpocket\.com|pocket\.co)' => 'brand.get-pocket',
        '(github\.com|github\.io)' => 'brand.github',
        'gitlab\.com' => 'brand.gitlab',
        'gitter\.im' => 'brand.gitter',
        'drive\.google\.com' => 'brand.google-drive',
        'play\.google\.com' => 'brand.google-play',
        'google\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'brand.google',
        'news\.ycombinator\.com' => 'brand.hacker-news',
        'imdb\.com' => 'brand.imdb',
        'instagram\.com' => 'brand.instagram',
        'jsfiddle\.net' => 'brand.jsfiddle',
        'keybase\.io' => 'brand.keybase',
        'kickstarter\.com' => 'brand.kickstarter',
        'last\.fm' => 'brand.lastfm',
        'linkedin\.com' => 'brand.linkedin',
        'mastodon\.social' => 'brand.mastodon',
        'medium\.com' => 'brand.medium',
        'meetup\.com' => 'brand.meetup',
        'microsoft\.com' => 'brand.microsoft',
        'mixcloud\.com' => 'brand.mixcloud',
        'npmjs\.com' => 'brand.npm',
        'openid\.net' => 'brand.openid',
        'patreon\.com' => 'brand.patreon',
        'paypal\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'brand.paypal',
        'php\.net' => 'brand.php',
        'pinterest\.com' => 'brand.pinterest',
        'playstation\.com' => 'brand.playstation',
        'producthunt\.com' => 'brand.product-hunt',
        'quora\.com' => 'brand.quora',
        'reddit\.com' => 'brand.reddit',
        'researchgate\.net' => 'brand.researchgate',
        'skype\.com' => 'brand.skype',
        'slack\.com' => 'brand.slack',
        'slideshare\.net' => 'brand.slideshare',
        'snapchat\.com' => 'brand.snapchat',
        'soundcloud\.com' => 'brand.soundcloud',
        'spotify\.com' => 'brand.spotify',
        'stackexchange\.com' => 'brand.stack-exchange',
        'stackoverflow\.com' => 'brand.stack-overflow',
        '(steampowered\.com|steamcommunity\.com)' => 'brand.steam',
        '(telegram\.org|t\.me)' => 'brand.telegram',
        'trello\.com' => 'brand.trello',
        'tripadvisor\.com' => 'brand.tripadvisor',
        'tumblr\.com' => 'brand.tumblr',
        'twitch\.tv' => 'brand.twitch',
        'twitter\.com' => 'brand.twitter',
        'vimeo\.com' => 'brand.vimeo',
        'weibo\.com' => 'brand.weibo',
        'wikipedia\.org' => 'brand.wikipedia-w',
        'wordpress\.(com|org)' => 'brand.wordpress',
        'xbox\.com' => 'brand.xbox',
        'xing\.com' => 'brand.xing',
        'yelp\.com' => 'brand.yelp',
        'youtube\.com' => 'brand.youtube',
    ];

    /**
     * Check if the given url matches an icon
     *
     * @param string $url
     * @return string
     */
    public static function mapLink(string $url): string
    {
        foreach (self::$iconMap as $pattern => $icon) {
            if (preg_match('/' . $pattern . '/i', $url)) {
                return $icon;
            }
        }

        return self::$defaultIcon;
    }
}
