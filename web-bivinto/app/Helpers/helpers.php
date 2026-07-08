<?php

use App\Models\Setting;

if (!function_exists('get_setting')) {
    /**
     * Lấy giá trị cấu hình từ bảng settings.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}
