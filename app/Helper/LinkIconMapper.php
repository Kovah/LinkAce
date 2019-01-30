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
        'foursquare.com' => 'fab-foursquare',
        '(getpocket.com|pocket.co)' => 'fab-get-pocket',
        'github.com' => 'fab-github',
        'gitlab.com' => 'fab-gitlab',
        'gitter.im' => 'fab-gitter',
        'drive.google.com' => 'fab-google-drive',
        'play.google.com' => 'fab-google-play',
        'google.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab-google',
        'news.ycombinator.com' => 'fab-hacker-news',
        'hackerrank.com' => 'fab-hackerrank',
        'houzz.com' => 'fab-houzz',
        'imdb.com' => 'fab-imdb',
        'instagram.com' => 'fab-instagram',
        'invisionapp.com' => 'fab-invision',
        'jsfiddle.net' => 'fab-jsfiddle',
        'kaggle.com' => 'fab-kaggle',
        'keybase.io' => 'fab-keybase',
        'kickstarter.com' => 'fab-kickstarter',
        'last.fm' => 'fab-lastfm',
        'leanpub.com' => 'fab-leanpub',
        'linkedin.com' => 'fab-linkedin-in',
        'mastodon.social' => 'fab-mastodon',
        'medium.com' => 'fab-medium-m',
        'meetup.com' => 'fab-meetup',
        'microsoft.com' => 'fab-microsoft',
        'mixcloud.com' => 'fab-mixcloud',
        'store.nintendo.com' => 'fab-nintendo-switch',
        'npmjs.com' => 'fab-npm',
        'openid.net' => 'fab-openid',
        'opensource.org' => 'fab-osi',
        'patreon.com' => 'fab-patreon ',
        'paypal.([a-zA-Z]*(\.)?[a-zA-Z]*)' => 'fab-paypal',
        'periscope.tv' => 'fab-periscope',
        'php.net' => 'fab-php',
        'pinterest.com' => 'fab-pinterest',
        'playstation.com' => 'fab-playstation',
        'producthunt.com' => 'fab-product-hunt',
        'python.org' => 'fab-python',
        'quora.com' => 'fab-quora',
        'readme.io' => 'fab-readme',
        'reddit.com' => 'fab-reddit',
        'researchgate.net' => 'fab-researchgate',
        'rocket.chat' => 'fab-rocketchat',
        'scribd.com' => 'fab-scribd',
        'skype.com' => 'fab-skype',
        'slack.com' => 'fab-slack',
        'slideshare.net' => 'fab-slideshare',
        'snapchat.com' => 'fab-snapchat',
        'soundcloud.com' => 'fab-soundcloud',
        'spotify.com' => 'fab-spotify',
        'squarespace.com' => 'fab-squarespace',
        'stackexchange.com' => 'fab-stack-exchange',
        'stackoverflow.com' => 'fab-stack-overflow',
        '(steampowered.com|steamcommunity.com)' => 'fab-steam',
        'strava.com' => 'fab-strava',
        '(telegram.org|t.me)' => 'fab-telegram',
        'trello.com' => 'fab-trello',
        'tripadvisor.com' => 'fab-tripadvisor',
        'tumblr.com' => 'fab-tumblr',
        'twitch.tv' => 'fab-twitch',
        'twitter.com' => 'fab-twitter',
        'vimeo.com' => 'fab-vimeo',
        'weibo.com' => 'fab-weibo',
        'wikipedia.org' => 'fab-wikipedia-w',
        'wordpress.com' => 'fab-wordpress',
        'wordpress.org' => 'fab-wordpress-simple',
        'xbox.com' => 'fab-xbox',
        'xing.com' => 'fab-xing',
        'yahoo.com' => 'fab-yahoo',
        'yelp.com' => 'fab-yelp',
        'youtube.com' => 'fab-youtube',
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
