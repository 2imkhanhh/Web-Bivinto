<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductSize;
use App\Models\StockHistory;
use App\Mail\OrderConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $order = Order::with(['user', 'items'])->findOrFail($id);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Không thay đổi gì nếu trạng thái giống nhau
        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('success', 'Trạng thái không thay đổi.');
        }

        DB::beginTransaction();
        try {
            $order->status = $newStatus;
            $order->save();

            // Trừ kho khi xác nhận đơn hàng
            if ($oldStatus === 'pending' && $newStatus === 'confirmed') {
                $this->deductStock($order);
            }

            // Hoàn kho khi hủy đơn (từ confirmed hoặc shipping)
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['confirmed', 'shipping'])) {
                $this->restoreStock($order);
            }

            DB::commit();

            // Gửi mail cho khách hàng nếu trạng thái đổi sang confirmed
            if ($oldStatus !== 'confirmed' && $newStatus === 'confirmed') {
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
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Trừ kho khi xác nhận đơn hàng.
     */
    private function deductStock(Order $order)
    {
        foreach ($order->items as $item) {
            if (!$item->product_size_id) continue;

            $productSize = ProductSize::find($item->product_size_id);
            if (!$productSize) continue;

            $oldStock = $productSize->stock;
            $newStock = max(0, $oldStock - $item->quantity);

            $productSize->stock = $newStock;
            $productSize->save();

            StockHistory::create([
                'product_size_id' => $productSize->id,
                'type' => 'out',
                'quantity' => -$item->quantity,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'note' => 'Đơn hàng #' . $order->order_code,
                'user_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Hoàn kho khi hủy đơn hàng.
     */
    private function restoreStock(Order $order)
    {
        foreach ($order->items as $item) {
            if (!$item->product_size_id) continue;

            $productSize = ProductSize::find($item->product_size_id);
            if (!$productSize) continue;

            $oldStock = $productSize->stock;
            $newStock = $oldStock + $item->quantity;

            $productSize->stock = $newStock;
            $productSize->save();

            StockHistory::create([
                'product_size_id' => $productSize->id,
                'type' => 'in',
                'quantity' => $item->quantity,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'note' => 'Hoàn kho - Hủy đơn hàng #' . $order->order_code,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function print($id)
    {
        $order = Order::with(['items.product', 'items.color', 'items.size'])
            ->findOrFail($id);

        return view('admin.orders.print', compact('order'));
    }
}