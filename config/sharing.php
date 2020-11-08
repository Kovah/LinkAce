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
            'icon' => 'icon.envelope',
        ],
        'facebook' => [
            'action' => 'https://www.facebook.com/sharer/sharer.php?u=#URL#',
            'icon' => 'icon.brand.facebook',
        ],
        'twitter' => [
            'action' => 'https://twitter.com/intent/tweet?text=#SHARETEXT#',
            'icon' => 'icon.brand.twitter',
        ],
        'reddit' => [
            'action' => 'http://www.reddit.com/submit?url=#URL#&title=#SUBJECT#',
            'icon' => 'icon.brand.reddit',
        ],
        'pinterest' => [
            'action' => 'http://pinterest.com/pin/create/button/?url=#URL#&description=#SUBJECT#',
            'icon' => 'icon.brand.pinterest',
        ],
        'whatsapp' => [
            'action' => 'whatsapp://send?text=#SHARETEXT#',
            'icon' => 'icon.brand.whatsapp',
        ],
        'telegram' => [
            'action' => 'tg://msg?text==#SHARETEXT#',
            'icon' => 'icon.brand.telegram',
        ],
        'wechat' => [
            'action' => 'https://www.addtoany.com/ext/wechat/share/#url=#URL#',
            'icon' => 'icon.brand.weixin',
        ],
        'sms' => [
            'action' => 'sms:?&body=#SHARETEXT#',
            'icon' => 'icon.sms',
        ],
        'skype' => [
            'action' => 'https://web.skype.com/share?url=#E-URL#',
            'icon' => 'icon.brand.skype',
        ],
        'hackernews' => [
            'action' => 'https://news.ycombinator.com/submitlink?u=#URL#&t=#SUBJECT#',
            'icon' => 'icon.brand.hacker-news',
        ],
        'mastodon' => [
            'action' => 'web+mastodon://share?text=#E-SHARETEXT#',
            'icon' => 'icon.brand.mastodon',
        ],
        'pocket' => [
            'action' => 'https://getpocket.com/save?url=#URL#',
            'icon' => 'icon.brand.get-pocket',
        ],
        'flipboard' => [
            'action' => 'https://share.flipboard.com/bookmarklet/popout?v=#SUBJECT#&url=#E-URL#',
            'icon' => 'icon.brand.flipboard',
        ],
        'evernote' => [
            'action' => 'https://www.evernote.com/clip.action?url=#E-URL#&title=#SUBJECT#',
            'icon' => 'icon.brand.evernote',
        ],
        'trello' => [
            'action' => 'https://trello.com/add-card?mode=popup&url=#E-URL#&name=#E-SUBJECT#&desc=',
            'icon' => 'icon.brand.trello',
        ],
        'buffer' => [
            'action' => 'https://buffer.com/add?url=#E-URL#&text=#E-SUBJECT#',
            'icon' => 'icon.brand.buffer',
        ],
        'tumblr' => [
            'action' => 'http://tumblr.com/share/link?url=#URL#&name=#SUBJECT#',
            'icon' => 'icon.brand.tumblr',
        ],
        'xing' => [
            'action' => 'https://www.xing.com/spi/shares/new?url=#URL#',
            'icon' => 'icon.brand.xing',
        ],
        'linkedin' => [
            'action' => 'https://www.linkedin.com/shareArticle?mini=true&url=#E-URL#&title=#E-SUBJECT#&summary=&source=AddToAny',
            'icon' => 'icon.brand.linkedin',
        ],
    ],
];
