<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'colors.sizes', 'colors.images'])->orderBy('id', 'desc')->get();
        $categories = Category::where('status', 'active')->get();
        return view('admin.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name);
            if (Product::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . time();
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status ?? 'active',
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Handle colors, sizes and images
            if ($request->has('colors')) {
                foreach ($request->colors as $index => $colorData) {
                    $color = $product->colors()->create([
                        'color_name' => $colorData['name'],
                        'color_code' => $colorData['code'] ?? null,
                    ]);

                    // Sizes
                    if (isset($colorData['sizes'])) {
                        foreach ($colorData['sizes'] as $sizeData) {
                            $color->sizes()->create([
                                'size_name' => $sizeData['name'],
                                'stock' => $sizeData['stock'] ?? 0,
                            ]);
                        }
                    }

                    // Images
                    if ($request->hasFile("colors.{$index}.images")) {
                        foreach ($request->file("colors.{$index}.images") as $imgIndex => $file) {
                            $path = $file->store('products', 'public');
                            $color->images()->create([
                                'product_id' => $product->id, // also link directly to product
                                'image_path' => $path,
                                'is_primary' => $imgIndex === 0 // first image is primary
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Thêm sản phẩm thành công!'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['colors.sizes', 'colors.images']));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $slug . '-' . time();
            }

            $product->update([
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status ?? 'active',
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Lọc ra các ảnh cũ mà người dùng muốn giữ lại
            $keptImages = [];
            if ($request->has('colors')) {
                foreach ($request->colors as $colorData) {
                    if (isset($colorData['existing_images'])) {
                        foreach ($colorData['existing_images'] as $path) {
                            $keptImages[] = $path;
                        }
                    }
                }
            }

            // Only delete images that are NOT in $keptImages
            foreach ($product->images as $image) {
                if (!in_array($image->image_path, $keptImages)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            
            $product->colors()->delete(); // cascade deletes sizes and images in DB

            // Recreate colors, sizes and images
            if ($request->has('colors')) {
                foreach ($request->colors as $index => $colorData) {
                    $color = $product->colors()->create([
                        'color_name' => $colorData['name'],
                        'color_code' => $colorData['code'] ?? null,
                    ]);

                    // Sizes
                    if (isset($colorData['sizes'])) {
                        foreach ($colorData['sizes'] as $sizeData) {
                            $color->sizes()->create([
                                'size_name' => $sizeData['name'],
                                'stock' => $sizeData['stock'] ?? 0,
                            ]);
                        }
                    }

                    // Images
                    if ($request->hasFile("colors.{$index}.images")) {
                        foreach ($request->file("colors.{$index}.images") as $imgIndex => $file) {
                            $path = $file->store('products', 'public');
                            $color->images()->create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'is_primary' => $imgIndex === 0
                            ]);
                        }
                    } else if (isset($colorData['existing_images'])) {
                        // User might send back existing image paths
                        foreach ($colorData['existing_images'] as $imgIndex => $path) {
                            $color->images()->create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'is_primary' => $imgIndex === 0
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công!'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Xóa sản phẩm thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi xóa sản phẩm.'], 500);
        }
    }
}