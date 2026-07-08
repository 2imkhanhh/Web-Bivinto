<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Mail\OrderCreatedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'items' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $guestToken = \Illuminate\Support\Facades\Cookie::get('guest_cart_token');

        // Lấy giỏ hàng
        $query = Cart::with(['product']);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('guest_cart_token', $guestToken);
        }

        $itemIds = explode(',', $request->items);
        $query->whereIn('id', $itemIds);

        $cartItems = $query->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Giỏ hàng của bạn đang trống'], 400);
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $shippingFee = 0;
        $totalAmount = $subtotal + $shippingFee;

        if (!$userId) {
            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                // Generate customer code
                $customerCode = 'KH' . date('ymd') . strtoupper(\Illuminate\Support\Str::random(4));
                $user = \App\Models\User::create([
                    'customer_code' => $customerCode,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'province' => $request->province,
                    'district' => $request->district,
                    'ward' => $request->ward,
                    'password' => null,
                ]);
            } else {
                // Update their delivery info
                $user->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'province' => $request->province,
                    'district' => $request->district,
                    'ward' => $request->ward,
                ]);
            }
            $userId = $user->id;
        }

        try {
            DB::beginTransaction();

            $orderCode = 'BVT' . date('ymd') . strtoupper(\Illuminate\Support\Str::random(5));

            // Tạo Order
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $userId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'province' => $request->province,
                'district' => $request->district,
                'ward' => $request->ward,
                'note' => $request->note,
                'payment_method' => 'COD',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // Tạo OrderItems
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_color_id' => $item->product_color_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity
                ]);
            }

            // Xóa giỏ hàng đã chọn
            Cart::whereIn('id', $cartItems->pluck('id'))->delete();

            DB::commit();

            // Gửi mail cho Admin
            try {
                $adminEmail = env('ADMIN_EMAIL', 'admin@bivinto.com');
                Mail::to($adminEmail)->send(new OrderCreatedMail($order));
            } catch (\Exception $e) {
            }

            session(['recent_order_code' => $order->order_code]);

            return response()->json([
                'message' => 'Đặt hàng thành công!',
                'redirect_url' => url('/thanh-toan/thanh-cong/' . $order->order_code)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Đã có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage()], 500);
        }
    }

    public function success($orderCode)
    {
        // Tìm đơn hàng bằng order_code
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        if ($order->user_id && $order->user_id !== auth()->id()) {
            if (session('recent_order_code') !== $orderCode) {
                abort(403);
            }
        }

        return view('order-success', compact('order'));
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('orders.index', compact('orders', 'status'));
    }

    public function show($orderCode)
    {
        $order = Order::with(['items.product', 'items.color', 'items.size'])
            ->where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function trackForm()
    {
        return view('orders.track');
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_code', $request->order_code)
            ->where('email', $request->email)
            ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng. Vui lòng kiểm tra lại thông tin.');
        }

        session(['tracked_order' => $order->order_code]);

        return redirect('/tra-cuu/' . $order->order_code);
    }

    public function trackShow($orderCode)
    {
        if (session('tracked_order') !== $orderCode && (!auth()->check() || !Order::where('order_code', $orderCode)->where('user_id', auth()->id())->exists())) {
            abort(403);
        }

        $order = Order::with(['items.product', 'items.color', 'items.size'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}