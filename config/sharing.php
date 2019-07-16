<?php
return [

    'defaults' => [
        'url' => config('app.url'),
        'subject' => 'sharing.subject',
        'sharetext' => 'sharing.sharetext',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sharing Services
    |--------------------------------------------------------------------------
    |
    | These services can be enabled to place a simple sharing button on every
    | link to easily share the link.
    |
    | Available placeholders:
    | #URL#             Plain URL of the link
    | #E-URL#            Encoded URL of the link
    | #SHARETEXT#       Longer text for sharing that includes the URL
    | #E-SHARETEXT#     Longer encoded text for sharing that includes the URL
    | #SUBJECT#         Short title or description for the link without URL
    | #E-SUBJECT#       Encoded short title or description for the link without URL
    |
    */
    'services' => [
        'email' => [
            'action' => 'mailto:?subject=#E-SUBJECT#&body=#E-SHARETEXT#',
            'icon' => 'fa fa-envelope',
        ],
        'facebook' => [
            'action' => 'https://www.facebook.com/sharer/sharer.php?u=#URL#',
            'icon' => 'fab fa-facebook-square',
        ],
        'twitter' => [
            'action' => 'https://twitter.com/intent/tweet?text=#SHARETEXT#',
            'icon' => 'fab fa-twitter-square',
        ],
        'reddit' => [
            'action' => 'http://www.reddit.com/submit?url=#URL#&title=#SUBJECT#',
            'icon' => 'fab fa-reddit-square',
        ],
        'pinterest' => [
            'action' => 'http://pinterest.com/pin/create/button/?url=#URL#&description=#SUBJECT#',
            'icon' => 'fab fa-pinterest-square',
        ],
        'whatsapp' => [
            'action' => 'whatsapp://send?text=#SHARETEXT#',
            'icon' => 'fab fa-whatsapp-square',
        ],
        'telegram' => [
            'action' => 'tg://msg?text==#SHARETEXT#',
            'icon' => 'fab fa-telegram',
        ],
        'wechat' => [
            'action' => 'https://www.addtoany.com/ext/wechat/share/#url=#URL#',
            'icon' => 'fab fa-weixin',
        ],
        'sms' => [
            'action' => 'sms:?&body=#SHARETEXT#',
            'icon' => 'fa fa-sms',
        ],
        'skype' => [
            'action' => 'https://web.skype.com/share?url=#E-URL#',
            'icon' => 'fab fa-skype',
        ],
        'hackernews' => [
            'action' => 'https://news.ycombinator.com/submitlink?u=#URL#&t=#SUBJECT#',
            'icon' => 'fab fa-hacker-news-square',
        ],
        'mastodon' => [
            'action' => 'web+mastodon://share?text=#E-SHARETEXT#',
            'icon' => 'fab fa-mastodon',
        ],
        'pocket' => [
            'action' => 'https://getpocket.com/save?url=#URL#',
            'icon' => 'fab fa-get-pocket',
        ],
        'flipboard' => [
            'action' => 'https://share.flipboard.com/bookmarklet/popout?v=#SUBJECT#&url=#E-URL#',
            'icon' => 'fab fa-flipboard',
        ],
        'evernote' => [
            'action' => 'https://www.evernote.com/clip.action?url=#E-URL#&title=#SUBJECT#',
            'icon' => 'fab fa-evernote',
        ],
        'trello' => [
            'action' => 'https://trello.com/add-card?mode=popup&url=#E-URL#&name=#E-SUBJECT#&desc=',
            'icon' => 'fab fa-trello',
        ],
        'buffer' => [
            'action' => 'https://buffer.com/add?url=#E-URL#&text=#E-SUBJECT#',
            'icon' => 'fab fa-buffer',
        ],
        'tumblr' => [
            'action' => 'http://tumblr.com/share/link?url=#URL#&name=#SUBJECT#',
            'icon' => 'fab fa-tumblr-square',
        ],
        'xing' => [
            'action' => 'https://www.xing.com/spi/shares/new?url=#URL#',
            'icon' => 'fab fa-xing-square',
        ],
        'linkedin' => [
            'action' => 'https://www.linkedin.com/shareArticle?mini=true&url=#E-URL#&title=#E-SUBJECT#&summary=&source=AddToAny',
            'icon' => 'fab fa-linkedin',
        ],
    ],
];
