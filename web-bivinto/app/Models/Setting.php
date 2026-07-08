<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'sort_order'];

    /**
     * Lấy giá trị setting theo key, có cache.
     */
    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Cập nhật hoặc tạo mới setting.
     */
    public static function set(string $key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('site_settings');

        return $setting;
    }

    /**
     * Lấy tất cả settings theo group.
     */
    public static function getGroup(string $group)
    {
        return self::where('group', $group)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Xóa cache settings.
     */
    public static function clearCache()
    {
        Cache::forget('site_settings');
    }
}
