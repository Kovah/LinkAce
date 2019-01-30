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
    public static $default_icon = 'fa fa-link';

    /** @var array Array holding the pattern => icon map, sorted alphabetically */
    public static $icon_map = [
        '500px\.com' => 'fab fa-500px',
        'adobe\.com' => 'fab fa-adobe',
        'aws\.amazon\.com' => 'fab fa-aws', // put before amazon pattern to keep it easy
        'amazon\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-amazon', // match amazon with every TLD
        'angel\.co' => 'fab fa-angellist',
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
        'digitalocean\.com' => 'fab fa-digital-ocean',
        '(discordapp\.com|discord\.gg)' => 'fab fa-discord',
        'meta\.discourse\.org' => 'fab fa-discourse',
        'docker\.com' => 'fab fa-docker',
        'dribbble\.com' => 'fab fa-dribbble',
        'dropbox\.com' => 'fab fa-dropbox',
        'drupal\.org' => 'fab fa-drupal',
        'ebay\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-ebay',
        'ello\.co' => 'fab fa-ello',
        'etsy\.com' => 'fab fa-etsy',
        '(facebook\.com|fb\.me)' => 'fab fa-facebook-f',
        'addons\.mozilla\.org\/[a-zA-Z-_]+\/firefox' => 'fab fa-firefox',
        'figma\.com' => 'fab fa-figma',
        'flickr\.com' => 'fab fa-flickr',
        '(flipboard\.com|flip\.it)' => 'fab fa-flipboard',
        'foursquare\.com' => 'fab fa-foursquare',
        '(getpocket\.com|pocket\.co)' => 'fab fa-get-pocket',
        '(github\.com|github\.io)' => 'fab fa-github',
        'gitlab\.com' => 'fab fa-gitlab',
        'gitter\.im' => 'fab fa-gitter',
        'drive\.google\.com' => 'fab fa-google-drive',
        'play\.google\.com' => 'fab fa-google-play',
        'google\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-google',
        'news\.ycombinator\.com' => 'fab fa-hacker-news',
        'hackerrank\.com' => 'fab fa-hackerrank',
        'houzz\.com' => 'fab fa-houzz',
        'imdb\.com' => 'fab fa-imdb',
        'instagram\.com' => 'fab fa-instagram',
        'invisionapp\.com' => 'fab fa-invision',
        'jsfiddle\.net' => 'fab fa-jsfiddle',
        'kaggle\.com' => 'fab fa-kaggle',
        'keybase\.io' => 'fab fa-keybase',
        'kickstarter\.com' => 'fab fa-kickstarter',
        'last\.fm' => 'fab fa-lastfm',
        'leanpub\.com' => 'fab fa-leanpub',
        'linkedin\.com' => 'fab fa-linkedin-in',
        'mastodon\.social' => 'fab fa-mastodon',
        'medium\.com' => 'fab fa-medium-m',
        'meetup\.com' => 'fab fa-meetup',
        'microsoft\.com' => 'fab fa-microsoft',
        'mixcloud\.com' => 'fab fa-mixcloud',
        'store\.nintendo\.com' => 'fab fa-nintendo-switch',
        'npmjs\.com' => 'fab fa-npm',
        'openid\.net' => 'fab fa-openid',
        'opensource\.org' => 'fab fa-osi',
        'patreon\.com' => 'fab fa-patreon ',
        'paypal\.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab fa-paypal',
        'periscope\.tv' => 'fab fa-periscope',
        'php\.net' => 'fab fa-php',
        'pinterest\.com' => 'fab fa-pinterest',
        'playstation\.com' => 'fab fa-playstation',
        'producthunt\.com' => 'fab fa-product-hunt',
        'python\.org' => 'fab fa-python',
        'quora\.com' => 'fab fa-quora',
        'readme\.io' => 'fab fa-readme',
        'reddit\.com' => 'fab fa-reddit',
        'researchgate\.net' => 'fab fa-researchgate',
        'rocket\.chat' => 'fab fa-rocketchat',
        'scribd\.com' => 'fab fa-scribd',
        'skype\.com' => 'fab fa-skype',
        'slack\.com' => 'fab fa-slack',
        'slideshare\.net' => 'fab fa-slideshare',
        'snapchat\.com' => 'fab fa-snapchat',
        'soundcloud\.com' => 'fab fa-soundcloud',
        'spotify\.com' => 'fab fa-spotify',
        'squarespace\.com' => 'fab fa-squarespace',
        'stackexchange\.com' => 'fab fa-stack-exchange',
        'stackoverflow\.com' => 'fab fa-stack-overflow',
        '(steampowered\.com|steamcommunity\.com)' => 'fab fa-steam',
        'strava\.com' => 'fab fa-strava',
        '(telegram\.org|t\.me)' => 'fab fa-telegram',
        'trello\.com' => 'fab fa-trello',
        'tripadvisor\.com' => 'fab fa-tripadvisor',
        'tumblr\.com' => 'fab fa-tumblr',
        'twitch\.tv' => 'fab fa-twitch',
        'twitter\.com' => 'fab fa-twitter',
        'vimeo\.com' => 'fab fa-vimeo',
        'weibo\.com' => 'fab fa-weibo',
        'wikipedia\.org' => 'fab fa-wikipedia-w',
        'wordpress\.com' => 'fab fa-wordpress',
        'wordpress\.org' => 'fab fa-wordpress-simple',
        'xbox\.com' => 'fab fa-xbox',
        'xing\.com' => 'fab fa-xing',
        'yahoo\.com' => 'fab fa-yahoo',
        'yelp\.com' => 'fab fa-yelp',
        'youtube\.com' => 'fab fa-youtube',
    ];

    /**
     * Check if the given url matches an icon
     *
     * @param $url
     * @return string
     */
    public static function mapLink($url): string
    {
        foreach (self::$icon_map as $pattern => $icon) {
            if (preg_match('/' . $pattern . '/i', $url)) {
                return $icon;
            }
        }

        return self::$default_icon;
    }
}
