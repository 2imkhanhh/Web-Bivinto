<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_color_id' => 'nullable|exists:product_colors,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('product_color_id', $request->product_color_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'product_color_id' => $request->product_color_id,
                'product_size_id' => $request->product_size_id,
                'quantity' => $request->quantity
            ]);
        }

        $totalItems = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng thành công',
            'cart_count' => $totalItems
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Đã cập nhật giỏ hàng']);
    }

    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    }
}