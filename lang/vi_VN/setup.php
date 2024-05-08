<?php

return [
    'setup' => 'Thiết lập',
    'continue' => 'Tiếp tục',
    'try_again' => 'Thử lại',

    'welcome' => 'Chào mừng đến với cài đặt LinkAce',
    'intro' => 'Theo các bước chỉ dẫn bạn sẽ thiết lập LinkAce sẵn sàng sử dụng.',
    'intro.step1' => 'Kiểm tra liệu mọi điều kiện có được đáp ứng.',
    'intro.step2' => 'Cài đặt 1 cơ sở dữ liệu và kiểm tra kết nối có thành công hay không.',
    'intro.step3' => 'Tạo tài khoản của bạn.',

    'check_requirements' => 'Kiểm tra tương thích',
    'requirements.php_version' => 'Phiên bản PHP >= 7.4.0',
    'requirements.extension_bcmath' => 'PHP Extension: BCMath',
    'requirements.extension_ctype' => 'PHP Extension: Ctype',
    'requirements.extension_json' => 'PHP Extension: JSON',
    'requirements.extension_mbstring' => 'PHP Extension: Mbstring',
    'requirements.extension_openssl' => 'PHP Extension: OpenSSL',
    'requirements.extension_pdo_mysql' => 'PHP Extension: PDO MySQL',
    'requirements.extension_tokenizer' => 'PHP Extension: Tokenizer',
    'requirements.extension_xml' => 'PHP Extension: XML',
    'requirements.env_writable' => 'File .env đang tồn tại và có thể ghi được',
    'requirements.storage_writable' => 'Thư mục /storage và /storage/logs có thể ghi được',

    'database_configuration' => 'Cấu hình cơ sở dữ liệu',
    'database_configure' => 'Cấu hình cơ sở dữ liệu',
    'database.intro' => 'Nếu bạn đã điền thông tin chi tiết trong file .env thì những trường đầu vào sẽ được điền sẵn. Mặt khác, điền những trường thông tin cần đúng với cơ sở dữ liệu của bạn.',
    'database.config_error' => 'Cơ sở dữ liệu sẽ không được cấu hình. Xin vui lòng kiểm tra chi tiết kết nối. Chi tiết:',
    'database.db_host' => 'Database Host',
    'database.db_port' => 'Database Port',
    'database.db_name' => 'Database Name',
    'database.db_user' => 'Database User',
    'database.db_password' => 'Database Password',
    'database.complete_hint' => 'Lưu cấu hình cơ sở dữ liệu và chuẩn bị nó cho việc sử dụng ứng dụng có thể cần vài giây, xin hãy kiên nhẫn.',

    'database.data_present' => 'Chú ý! Chúng tôi tìm thấy dữ liệu trong cơ sở dữ liệu bạn chỉ định! Xin vui lòng chắc chắn rằng bạn cần có 1 bản sao lưu cơ sở dữ liệu và xác nhận xóa toàn bộ dữ liệu.',
    'database.overwrite_data' => 'Tôi xác nhận rằng mọi mọi dữ liệu sẽ bị xóa và ghi đè với cơ sở dữ liệu LinkAce mới',

    'account_setup' => 'Thiết lập tài khoản',
    'account_setup.intro' => 'Để có thể bắt đầu, bạn cần phải tạo tài khoản người dùng.',
    'account_setup.name' => 'Nhập tên của bạn',
    'account_setup.email' => 'Nhập địa chỉ email của bạn',
    'account_setup.password' => 'Nhập 1 mật khẩu mạnh',
    'account_setup.password_requirements' => 'Dài tối thiểu: 10 ký tự',
    'account_setup.password_confirmed' => 'Xác nhận mật khẩu của bạn',
    'account_setup.create' => 'Tạo tài khoản',

    'complete' => 'Thiết lập hoàn tất!',
    'outro' => 'Bạn đã hoàn thành việc cài đặt và có thể sử dụng LinkAce! Nếu bạn đã đăng nhập thì có thể bắt đầu bookmark được rồi.',
];
