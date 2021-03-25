<?php
return [
    'settings' => '设置',
    'user_settings' => '用户设置',
    'account_settings' => '账号设置',
    'app_settings' => '应用程序设置',
    'system_settings' => '系统设置',
    'guest_settings' => '访客设置',

    'language' => '语言',
    'timezone' => '时区',
    'date_format' => '日期格式',
    'time_format' => '时间格式',
    'listitem_count' => '列表中的项目数',

    'links_new_tab' => '始终在新的标签页打开外部链接',

    'markdown_for_text' => '',

    'privacy' => '隐私',
    'links_private_default' => '默认私密链接',
    'links_private_default_help' => '启用此选项将默认所有新链接为私密链接',
    'notes_private_default' => '默认私密笔记',
    'notes_private_default_help' => '启用此选项将默认所有新注释为私密链接',
    'tags_private_default' => '默认私密标签',
    'tags_private_default_help' => '启用此选项将默认所有新标签都是私有的',
    'lists_private_default' => '默认私密列表',
    'lists_private_default_help' => '启用此选项将默认所有新列表都是私有的',

    'archive_backups' => 'Wayback Machine 备份',
    'archive_backups_help' => '如果启用，LinkAce将告诉<a href="https://archive.org/"> Wayback Machine </a>备份您的链接。 Wayback Machine由非营利组织Internet Archive提供支持。 请考虑<a href="https://archive.org/donate/">捐赠给Internet Archive </a>。',
    'archive_backups_enabled' => '启用备份',
    'archive_backups_enabled_help' => '如果启用，非私人链接将存档在 Internet Archive 中。',
    'archive_private_backups_enabled' => '启用私密链接备份',
    'archive_private_backups_enabled_help' => '如果启用，也将保存私有链接。必须启用备份。',

    'display_mode' => '链接显示为',
    'display_mode_list_detailed' => '列出更多信息',
    'display_mode_list_simple' => '列出部分信息',
    'display_mode_cards' => '细节较少的卡片',

    'sharing' => '链接共享',
    'sharing_help' => '启用您想要显示链接的所有服务，以便能够轻松地通过单击分享链接。',
    'sharing_toggle' => '切换所有开关',

    'darkmode' => '夜间模式',
    'darkmode_help' => '您可以选择永久开启或根据您的设备设置自动开启。 (<small><a href="https://caniuse.com/#search=prefers-color-scheme">点击这里检查</a> 如果您的浏览器支持自动检测</small>)',
    'darkmode_disabled' => '已禁用',
    'darkmode_auto' => '自动开启',
    'darkmode_permanent' => '始终开启',

    'save_settings' => '保存设置',
    'settings_saved' => '设置已更新！',

    'bookmarklet' => '小书签',
    'bookmarklet_button' => '拖动至您的书签栏或右键保存至书签',
    'bookmarklet_help' => '将小书签添加到您的浏览器中，以便您从访问的站点快速添加链接，而无需手动打开 LinkAce 。',

    'change_password' => '修改密码',
    'old_password' => '旧密码',
    'new_password' => '新密码',
    'new_password2' => '重复输入新密码',
    'password_updated' => '密码修改完成！',
    'old_password_invalid' => '旧密码无效！',

    'two_factor_auth' => '',
    'two_factor_enable' => '',
    'two_factor_disable' => '',
    'two_factor_setup_app' => '',
    'two_factor_setup_url' => '',
    'two_factor_recovery_codes' => '',
    'two_factor_recovery_codes_view' => '',
    'two_factor_regenerate_recovery_codes' => '',

    'api_token' => 'API 令牌',
    'api_token_generate' => '生成令牌',
    'api_token_generate_confirm' => '您确定要生成新令牌吗？',
    'api_token_help' => 'API 令牌可以用来从其他应用程序或脚本访问 LinkAce。',
    'api_token_generate_info' => '注意：如果你已经有 API 令牌，生成一个新的令牌会破坏所有现有的集成！',
    'api_token_generate_failure' => '无法生成新的 API 令牌。请检查您的浏览器控制台和应用程序日志以获取更多信息。',

    'sys_page_title' => '页面标题',
    'sys_guest_access' => '启用访客访问',
    'sys_guest_access_help' => '如果启用，访客将能够看到所有非私密链接。',

    'cron_token' => 'Cron 令牌',
    'cron_token_generate' => '生成令牌',
    'cron_token_generate_confirm' => '您确定要生成新令牌吗？',
    'cron_token_help' => '使用 cron 令牌来运行 cron 服务以检查死链接或运行备份。',
    'cron_token_url' => '将你的cron点到以下URL： <span class="cron-token-url">:route</span>',
    'cron_token_generate_info' => '注意：如果你已经有一个cron token，生成一个新的 cron 任务将会中断现有的 cron 任务！',
    'cron_token_generate_failure' => '无法生成新的 cron 令牌。请检查您的浏览器控制台和应用程序日志以获取更多信息。',
    'cron_token_auth_failure' => '提供的 cron 令牌无效',
    'cron_execute_successful' => 'Cron 成功执行',

    'update_check' => '检查更新',
    'update_check_running' => '正在检查更新…',
    'update_check_version_found' => '发现新版本 #VERSION# 可用。',
    'update_check_success' => '没有发现更新。',
    'update_check_failed' => '无法检查更新。',

    'guest_settings_info' => '如果访客模式已启用，下面的设置将应用于访客访问您的网站。',
];
