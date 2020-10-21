<?php

use App\Helper\LinkIconMapper;
use App\Models\Link;
use Illuminate\Database\Migrations\Migration;

class MigrateLinkIcons extends Migration
{
    /** @var array */
    protected $changedLinks = [
        'fab fa-500px' => 'brand.500px',
        'fab fa-amazon' => 'brand.amazon',
        'fab fa-app-store' => 'brand.app-store-ios',
        'fab fa-apple' => 'brand.apple',
        'fab fa-artstation' => 'brand.artstation',
        'fab fa-atlassian' => 'brand.atlassian',
        'fab fa-bandcamp' => 'brand.bandcamp',
        'fab fa-behance' => 'brand.behance',
        'fab fa-bitbucket' => 'brand.bitbucket',
        'fab fa-bitcoin' => 'brand.bitcoin',
        'fab fa-blogger-b' => 'brand.blogger-b',
        'fab fa-chrome' => 'brand.chrome',
        'fab fa-codepen' => 'brand.codepen',
        'fab fa-dev' => 'brand.dev',
        'fab fa-deviantart' => 'brand.deviantart',
        'fab fa-discord' => 'brand.discord',
        'fab fa-docker' => 'brand.docker',
        'fab fa-dribbble' => 'brand.dribbble',
        'fab fa-dropbox' => 'brand.dropbox',
        'fab fa-ebay' => 'brand.ebay',
        'fab fa-etsy' => 'brand.etsy',
        'fab fa-facebook-square' => 'brand.facebook',
        'fab fa-firefox' => 'brand.firefox',
        'fab fa-flickr' => 'brand.flickr',
        'fab fa-flipboard' => 'brand.flipboard',
        'fab fa-get-pocket' => 'brand.get-pocket',
        'fab fa-github' => 'brand.github',
        'fab fa-gitlab' => 'brand.gitlab',
        'fab fa-gitter' => 'brand.gitter',
        'fab fa-google' => 'brand.google',
        'fab fa-google-drive' => 'brand.google-drive',
        'fab fa-google-play' => 'brand.google-play',
        'fab fa-hacker-news' => 'brand.hacker-news',
        'fab fa-hacker-news-square' => 'brand.hacker-news',
        'fab fa-imdb' => 'brand.imdb',
        'fab fa-instagram' => 'brand.instagram',
        'fab fa-jsfiddle' => 'brand.jsfiddle',
        'fab fa-keybase' => 'brand.keybase',
        'fab fa-kickstarter' => 'brand.kickstarter',
        'fab fa-lastfm' => 'brand.lastfm',
        'fab fa-linkedin-in' => 'brand.linkedin',
        'fab fa-mastodon' => 'brand.mastodon',
        'fab fa-medium-m' => 'brand.medium',
        'fab fa-meetup' => 'brand.meetup',
        'fab fa-microsoft' => 'brand.microsoft',
        'fab fa-mixcloud' => 'brand.mixcloud',
        'fab fa-npm' => 'brand.npm',
        'fab fa-openid' => 'brand.openid',
        'fab fa-patreon ' => 'brand.patreon',
        'fab fa-paypal' => 'brand.paypal',
        'fab fa-php' => 'brand.php',
        'fab fa-pinterest-square' => 'brand.pinterest',
        'fab fa-playstation' => 'brand.playstation',
        'fab fa-product-hunt' => 'brand.product-hunt',
        'fab fa-quora' => 'brand.quora',
        'fab fa-reddit-square' => 'brand.reddit',
        'fab fa-researchgate' => 'brand.researchgate',
        'fab fa-skype' => 'brand.skype',
        'fab fa-slack' => 'brand.slack',
        'fab fa-slideshare' => 'brand.slideshare',
        'fab fa-snapchat' => 'brand.snapchat',
        'fab fa-soundcloud' => 'brand.soundcloud',
        'fab fa-spotify' => 'brand.spotify',
        'fab fa-stack-exchange' => 'brand.stack-exchange',
        'fab fa-stack-overflow' => 'brand.stack-overflow',
        'fab fa-steam' => 'brand.steam',
        'fab fa-telegram' => 'brand.telegram',
        'fab fa-trello' => 'brand.trello',
        'fab fa-tripadvisor' => 'brand.tripadvisor',
        'fab fa-tumblr-square' => 'brand.tumblr',
        'fab fa-twitch' => 'brand.twitch',
        'fab fa-twitter-square' => 'brand.twitter',
        'fab fa-vimeo' => 'brand.vimeo',
        'fab fa-weibo' => 'brand.weibo',
        'fab fa-wikipedia-w' => 'brand.wikipedia-w',
        'fab fa-wordpress' => 'brand.wordpress',
        'fab fa-xbox' => 'brand.xbox',
        'fab fa-xing-square' => 'brand.xing',
        'fab fa-yelp' => 'brand.yelp',
        'fab fa-youtube' => 'brand.youtube',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Link::withTrashed()->chunk(100, function ($links) {
            /** @var Link $link */
            foreach ($links as $link) {
                $link->icon = $this->changedLinks[$link->icon] ?? LinkIconMapper::$defaultIcon;
                $link->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
