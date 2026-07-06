<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $query = Product::with(['colors.sizes', 'images'])
            ->withSum('colors as total_stock', 'sizes.stock');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = Product::with(['colors.sizes', 'images'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        foreach ($products as $product) {
            $totalStock = 0;
            if ($product->colors) {
                foreach ($product->colors as $color) {
                    if ($color->sizes) {
                        foreach ($color->sizes as $size) {
                            $totalStock += $size->stock;
                        }
                    }
                }
            }
            $product->total_stock = $totalStock;
        }

        return Inertia::render('Admin/Inventory', [
            'products' => $products,
            'filters' => ['search' => $search],
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['colors.sizes', 'colors.images', 'images'])->findOrFail($id);

        return Inertia::render('Admin/InventoryDetail', [
            'product' => $product,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'adjustments' => 'required|array',
            'adjustments.*.product_size_id' => 'required|exists:product_sizes,id',
            'adjustments.*.new_stock' => 'required|integer|min:0',
            'adjustments.*.note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->adjustments as $adj) {
                $productSize = ProductSize::findOrFail($adj['product_size_id']);
                $oldStock = $productSize->stock;
                $newStock = (int) $adj['new_stock'];

                if ($oldStock === $newStock) continue;

                $quantity = $newStock - $oldStock;
                $type = $quantity > 0 ? 'in' : 'out';

                $productSize->stock = $newStock;
                $productSize->save();

                StockHistory::create([
                    'product_size_id' => $productSize->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'stock_before' => $oldStock,
                    'stock_after' => $newStock,
                    'note' => $adj['note'] ?? 'Điều chỉnh thủ công',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật tồn kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function history($productSizeId)
    {
        $histories = StockHistory::with('user')
            ->where('product_size_id', $productSizeId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($histories);
    }
}
