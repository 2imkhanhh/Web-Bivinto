<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Hiển thị trang cài đặt.
     */
    public function index()
    {
        // Các nhóm DB gộp vào tab nào
        $tabs = [
            'homepage' => [
                'label' => 'Trang Chủ',
                'db_groups' => ['banner', 'about', 'business'],
            ],
            'aboutpage' => [
                'label' => 'Về Bivinto',
                'db_groups' => ['aboutpage_hero', 'aboutpage_story', 'aboutpage_meaning', 'aboutpage_belief'],
            ],
            'collabpage' => [
                'label' => 'Hợp Tác',
                'db_groups' => ['collab_banner', 'collab_giacong', 'collab_nhapsi', 'collab_contact'],
            ],
            'policypage' => [
                'label' => 'Chính Sách',
                'db_groups' => ['policy_general', 'policy_items'],
            ],
            'footer' => [
                'label' => 'Footer',
                'db_groups' => ['footer'],
            ],
        ];

        // Map tên nhóm DB sang label hiển thị
        $sectionLabels = [
            'banner' => 'Banner',
            'about' => 'Về Bivinto',
            'business' => 'Lĩnh Vực Hoạt Động',
            'footer' => 'Footer',
            'aboutpage_hero' => 'Banner',
            'aboutpage_story' => 'Câu Chuyện',
            'aboutpage_meaning' => 'Ý Nghĩa Thương Hiệu',
            'aboutpage_belief' => 'Chúng Tôi Tin Rằng',
            'collab_banner' => 'Banner',
            'collab_giacong' => 'Liên Hệ Gia Công',
            'collab_nhapsi' => 'Liên Hệ Nhập Sỉ',
            'collab_contact' => 'Form Liên Hệ',
            'policy_general' => 'Chung',
            'policy_items' => 'Các Mục Chính Sách',
        ];

        $mapItem = function ($item) {
            if ($item->type === 'image' && $item->value) {
                if (str_starts_with($item->value, 'settings/')) {
                    $item->display_url = asset('storage/' . $item->value);
                } else {
                    $item->display_url = asset($item->value);
                }
            }
            return $item;
        };

        $settings = [];
        foreach ($tabs as $tabKey => $tab) {
            $sections = [];
            foreach ($tab['db_groups'] as $dbGroup) {
                $items = Setting::where('group', $dbGroup)
                    ->orderBy('sort_order')
                    ->get()
                    ->map($mapItem);

                if ($items->isNotEmpty()) {
                    $sections[] = [
                        'key' => $dbGroup,
                        'label' => $sectionLabels[$dbGroup] ?? $dbGroup,
                        'items' => $items,
                    ];
                }
            }

            $settings[$tabKey] = [
                'label' => $tab['label'],
                'sections' => $sections,
            ];
        }

        return Inertia::render('Admin/Settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Cập nhật settings.
     */
    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // Bỏ qua các trường không phải setting key
            if ($key === '_method') continue;

            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            if ($setting->type === 'image') {
                // Nếu value là file upload
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    
                    // Xóa ảnh cũ nếu nằm trong storage
                    if ($setting->value && str_starts_with($setting->value, 'settings/')) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $path = $file->store('settings', 'public');
                    $setting->value = $path;
                    $setting->save();
                }
                // Nếu không upload file mới thì giữ nguyên
            } else {
                $setting->value = $value;
                $setting->save();
            }
        }

        Setting::clearCache();

        return redirect()->back()->with('success', 'Cài đặt đã được cập nhật thành công.');
    }
}
