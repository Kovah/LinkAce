<?php
return [
    'timeout' => 10,
    'parser' => \Kovah\HtmlMeta\HtmlMetaParser::class,
    'user_agents' => [
        env('APP_USER_AGENT', 'LinkAce/1 (https://github.com/Kovah/LinkAce)'),
    ],
];
