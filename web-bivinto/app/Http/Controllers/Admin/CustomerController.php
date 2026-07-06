<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = User::where('role', '!=', 'admin')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Customers', [
            'customers' => $customers,
            'filters' => ['search' => $search]
        ]);
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role === 'admin') {
            return redirect()->back()->withErrors(['error' => 'Không thể thay đổi quyền của Quản trị viên khác.']);
        }

        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $customer->update([
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật quyền thành công!');
    }

    public function destroy(User $customer)
    {
        if ($customer->role === 'admin') {
            return redirect()->back()->withErrors(['error' => 'Không thể xoá tài khoản Quản trị viên.']);
        }

        // Kiểm tra xem khách hàng có đơn hàng nào chưa hoàn thành/hủy không
        $activeOrders = Order::where('user_id', $customer->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        if ($activeOrders > 0) {
            return redirect()->back()->withErrors(['error' => 'Không thể xóa khách hàng đang có đơn hàng chưa hoàn tất.']);
        }

        // Xoá khách hàng
        $customer->delete();

        return redirect()->back()->with('success', 'Đã xóa khách hàng thành công!');
    }
}
