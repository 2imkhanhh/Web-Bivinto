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
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // Lấy giỏ hàng
        $cartItems = Cart::with(['product'])->where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Giỏ hàng của bạn đang trống'], 400);
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $shippingFee = 0;
        $totalAmount = $subtotal + $shippingFee;

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

            // Xóa giỏ hàng
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // Gửi mail cho Admin
            try {
                $adminEmail = env('ADMIN_EMAIL', 'admin@bivinto.com');
                Mail::to($adminEmail)->send(new OrderCreatedMail($order));
            } catch (\Exception $e) {
            }

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

        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
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
}