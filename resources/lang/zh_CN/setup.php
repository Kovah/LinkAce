<?php

return [
    'setup' => '安装',
    'continue' => '继续',
    'try_again' => '重试',

    'welcome' => '欢迎使用 LinkAce',
    'intro' => '完成以下步骤进行 LinkAce 安装',
    'intro.step1' => '检查是否符合所有要求。',
    'intro.step2' => '设置数据库并检查连接是否成功。',
    'intro.step3' => '创建您的帐户。',

    'check_requirements' => '检查要求',
    'requirements.php_version' => 'PHP version >= 7.2.0',
    'requirements.extension_bcmath' => 'PHP Extension: BCMath',
    'requirements.extension_ctype' => 'PHP Extension: Ctype',
    'requirements.extension_json' => 'PHP Extension: JSON',
    'requirements.extension_mbstring' => 'PHP Extension: Mbstring',
    'requirements.extension_openssl' => 'PHP Extension: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP Extension: PDO',
    'requirements.extension_tokenizer' => 'PHP Extension: Tokenizer',
    'requirements.extension_xml' => 'PHP Extension: XML',
    'requirements.env_writable' => '.env 文件已存在并可写',
    'requirements.storage_writable' => '',

    'database_configuration' => '数据库配置',
    'database_configure' => '配置数据库',
    'database.intro' => '如果您已经在 .env 文件中填写了数据库详细信息，输入字段应该预先填写。 否则，请填写相应的数据库信息。',
    'database.config_error' => '数据库无法配置。请检查您的连接详细信息：',
    'database.db_host' => '数据库主机',
    'database.db_port' => '数据库端口',
    'database.db_name' => '数据库名称',
    'database.db_user' => '数据库用户',
    'database.db_password' => '数据库密码',
    'database.complete_hint' => '保存数据库配置并准备使用应用程序可能需要几秒钟，请耐心等待。',

    'database.data_present' => '注意！我们在您指定的数据库中找到数据！ 请确保您有该数据库的备份并确认删除所有数据。',
    'database.overwrite_data' => '我确认所有数据应该被删除并用新的 LinkAce 数据库覆盖',

    'account_setup' => '账户设置',
    'account_setup.intro' => '在您开始之前，您必须创建您的用户帐户。',
    'account_setup.name' => '输入您的名称',
    'account_setup.email' => '输入您电子邮箱',
    'account_setup.password' => '输入较强的密码。',
    'account_setup.password_requirements' => '最小长度：10 个字符',
    'account_setup.password_confirmed' => '确认您的密码',
    'account_setup.create' => '创建帐户',

    'complete' => '安装完成！',
    'outro' => '您已完成设置，现在可以使用 LinkAce！您已登录并可以马上开始使用书签。',
];
