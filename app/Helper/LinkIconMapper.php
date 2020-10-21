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
    public static $defaultIcon = 'link';

    /** @var array Array holding the pattern => icon map, sorted alphabetically */
    public static $iconMap = [
        '500px\.com' => 'fab fa-500px',
        'amazon\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-amazon',
        'itunes\.apple\.com\/app' => 'fab fa-app-store',
        'apple\.com' => 'fab fa-apple',
        'artstation\.com' => 'fab fa-artstation',
        'atlassian\.(com|net)' => 'fab fa-atlassian',
        'bandcamp\.com' => 'fab fa-bandcamp',
        'behance\.net' => 'fab fa-behance',
        'bitbucket\.org' => 'fab fa-bitbucket',
        'blockchain\.com' => 'fab fa-bitcoin',
        'blogger\.com' => 'fab fa-blogger-b',
        'chrome\.google\.com\/webstore' => 'fab fa-chrome',
        'codepen\.io' => 'fab fa-codepen',
        'dev\.to' => 'fab fa-dev',
        'deviantart\.com' => 'fab fa-deviantart',
        '(discordapp\.com|discord\.gg)' => 'fab fa-discord',
        'docker\.com' => 'fab fa-docker',
        'dribbble\.com' => 'fab fa-dribbble',
        'dropbox\.com' => 'fab fa-dropbox',
        'ebay\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-ebay',
        'etsy\.com' => 'fab fa-etsy',
        '(facebook\.com|fb\.me)' => 'fab fa-facebook-square',
        'addons\.mozilla\.org\/[a-zA-Z-_]+\/firefox' => 'fab fa-firefox',
        'flickr\.com' => 'fab fa-flickr',
        '(flipboard\.com|flip\.it)' => 'fab fa-flipboard',
        '(getpocket\.com|pocket\.co)' => 'fab fa-get-pocket',
        '(github\.com|github\.io)' => 'fab fa-github',
        'gitlab\.com' => 'fab fa-gitlab',
        'gitter\.im' => 'fab fa-gitter',
        'drive\.google\.com' => 'fab fa-google-drive',
        'play\.google\.com' => 'fab fa-google-play',
        'google\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-google',
        'news\.ycombinator\.com' => 'fab fa-hacker-news-square',
        'imdb\.com' => 'fab fa-imdb',
        'instagram\.com' => 'fab fa-instagram',
        'jsfiddle\.net' => 'fab fa-jsfiddle',
        'keybase\.io' => 'fab fa-keybase',
        'kickstarter\.com' => 'fab fa-kickstarter',
        'last\.fm' => 'fab fa-lastfm',
        'linkedin\.com' => 'fab fa-linkedin-in',
        'mastodon\.social' => 'fab fa-mastodon',
        'medium\.com' => 'fab fa-medium-m',
        'meetup\.com' => 'fab fa-meetup',
        'microsoft\.com' => 'fab fa-microsoft',
        'mixcloud\.com' => 'fab fa-mixcloud',
        'npmjs\.com' => 'fab fa-npm',
        'openid\.net' => 'fab fa-openid',
        'patreon\.com' => 'fab fa-patreon ',
        'paypal\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-paypal',
        'php\.net' => 'fab fa-php',
        'pinterest\.com' => 'fab fa-pinterest-square',
        'playstation\.com' => 'fab fa-playstation',
        'producthunt\.com' => 'fab fa-product-hunt',
        'quora\.com' => 'fab fa-quora',
        'reddit\.com' => 'fab fa-reddit-square',
        'researchgate\.net' => 'fab fa-researchgate',
        'skype\.com' => 'fab fa-skype',
        'slack\.com' => 'fab fa-slack',
        'slideshare\.net' => 'fab fa-slideshare',
        'snapchat\.com' => 'fab fa-snapchat',
        'soundcloud\.com' => 'fab fa-soundcloud',
        'spotify\.com' => 'fab fa-spotify',
        'stackexchange\.com' => 'fab fa-stack-exchange',
        'stackoverflow\.com' => 'fab fa-stack-overflow',
        '(steampowered\.com|steamcommunity\.com)' => 'fab fa-steam',
        '(telegram\.org|t\.me)' => 'fab fa-telegram',
        'trello\.com' => 'fab fa-trello',
        'tripadvisor\.com' => 'fab fa-tripadvisor',
        'tumblr\.com' => 'fab fa-tumblr-square',
        'twitch\.tv' => 'fab fa-twitch',
        'twitter\.com' => 'fab fa-twitter-square',
        'vimeo\.com' => 'fab fa-vimeo',
        'weibo\.com' => 'fab fa-weibo',
        'wikipedia\.org' => 'fab fa-wikipedia-w',
        'wordpress\.(com|org)' => 'fab fa-wordpress',
        'xbox\.com' => 'fab fa-xbox',
        'xing\.com' => 'fab fa-xing-square',
        'yelp\.com' => 'fab fa-yelp',
        'youtube\.com' => 'fab fa-youtube',
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
