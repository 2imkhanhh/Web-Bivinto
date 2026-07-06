<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Order::with('user')->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Orders', [
            'orders' => $orders,
            'filters' => ['status' => $status]
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'items.color', 'items.size'])
            ->findOrFail($id);

        return Inertia::render('Admin/OrderDetails', [
            'order' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled'
        ]);

        $order = Order::with('user')->findOrFail($id);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Gửi mail cho khách hàng nếu trạng thái đổi sang confirmed
        if ($oldStatus !== 'confirmed' && $order->status === 'confirmed') {
            $customerEmail = $order->email ?? ($order->user ? $order->user->email : null);
            if ($customerEmail) {
                try {
                    Mail::to($customerEmail)->send(new OrderConfirmedMail($order));
                } catch (\Exception $e) {
                    // Ignore mail error
                }
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công');
    }

    public function print($id)
    {
        $order = Order::with(['items.product', 'items.color', 'items.size'])
            ->findOrFail($id);

        return view('admin.orders.print', compact('order'));
    }
}