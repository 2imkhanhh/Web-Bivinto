<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Lấy tất cả danh mục sắp xếp theo thứ tự hiển thị (STT)
        $categories = Category::with('parent')->orderBy('display_order', 'asc')->get();
        // Lấy danh mục cha 
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.categories', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $slug = Str::slug($request->name);

        // Ensure slug is unique if needed, but unique name mostly covers it.
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'display_order' => $request->input('display_order', 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công!',
            'category' => $category->load('parent')
        ]);
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
        ]);

        if ($request->parent_id == $category->id) {
            return response()->json(['success' => false, 'message' => 'Danh mục cha không hợp lệ.'], 400);
        }

        $slug = Str::slug($request->name);
        if ($category->name !== $request->name) {
            $count = Category::where('slug', $slug)->where('id', '!=', $category->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }
        } else {
            $slug = $category->slug;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'display_order' => $request->input('display_order', 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật danh mục thành công!',
            'category' => $category->load('parent')
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa vì còn danh mục con.'], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công!'
        ]);
    }
}
