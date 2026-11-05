<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailSettingController extends Controller
{
    public function index()
    {
        $items = Setting::where('group', 'email_config')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/MailSettings', [
            'settings' => $items,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->where('group', 'email_config')->first();
            if ($setting) {
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
