<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailSettingController extends Controller
{
    /**
     * Hiển thị trang cấu hình email.
     */
    public function index()
    {
        // Lấy tất cả cài đặt thuộc group email_config
        $items = Setting::where('group', 'email_config')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/MailSettings', [
            'settings' => $items,
        ]);
    }

    /**
     * Cập nhật cấu hình email.
     */
    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->where('group', 'email_config')->first();
            if ($setting) {
                // Nếu là trường password mà gửi lên rỗng (không đổi pass) thì bỏ qua
                if ($setting->type === 'password' && empty($value)) {
                    continue;
                }
                
                $setting->value = $value;
                $setting->save();
            }
        }

        Setting::clearCache();

        return redirect()->back()->with('success', 'Cấu hình Email đã được cập nhật thành công.');
    }
}
