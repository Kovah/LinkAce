<?php
return [
    'timeout' => env('META_GENERATION_TIMEOUT', 10),
    'parser' => \Kovah\HtmlMeta\HtmlMetaParser::class,
    'default_accept' => 'text/html',
    'block_private_ips' => !(bool)env('META_ALLOW_PRIVATE_IP_RANGES', false),
    'user_agents' => [
        env('APP_USER_AGENT', 'LinkAce/2 (https://github.com/Kovah/LinkAce)'),
    ],
    'custom_headers' => env('META_GENERATION_CUSTOM_HEADERS'),
];
