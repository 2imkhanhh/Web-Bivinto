<?php

$defaults = [
    ['key' => 'mail_host', 'value' => env('MAIL_HOST', 'smtp.gmail.com'), 'type' => 'text', 'group' => 'email_config', 'label' => 'SMTP Host', 'sort_order' => 1],
    ['key' => 'mail_port', 'value' => env('MAIL_PORT', '587'), 'type' => 'text', 'group' => 'email_config', 'label' => 'SMTP Port', 'sort_order' => 2],
    ['key' => 'mail_username', 'value' => env('MAIL_USERNAME', ''), 'type' => 'text', 'group' => 'email_config', 'label' => 'Tài khoản SMTP (Username)', 'sort_order' => 3],
    ['key' => 'mail_password', 'value' => env('MAIL_PASSWORD', ''), 'type' => 'password', 'group' => 'email_config', 'label' => 'Mật khẩu SMTP (App Password)', 'sort_order' => 4],
    ['key' => 'mail_encryption', 'value' => env('MAIL_ENCRYPTION', 'tls'), 'type' => 'text', 'group' => 'email_config', 'label' => 'Mã hóa (TLS/SSL)', 'sort_order' => 5],
    ['key' => 'mail_from_address', 'value' => env('MAIL_FROM_ADDRESS', ''), 'type' => 'text', 'group' => 'email_config', 'label' => 'Email gửi đi (Từ địa chỉ)', 'sort_order' => 6],
    ['key' => 'mail_from_name', 'value' => env('MAIL_FROM_NAME', ''), 'type' => 'text', 'group' => 'email_config', 'label' => 'Tên người gửi', 'sort_order' => 7]
];

foreach ($defaults as $data) {
    \App\Models\Setting::firstOrCreate(['key' => $data['key']], $data);
}
echo 'Seeded successfully.' . PHP_EOL;
