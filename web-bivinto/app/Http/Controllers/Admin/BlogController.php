<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderByRaw('position = 0, position ASC')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        return Inertia::render('Admin/Blogs', [
            'blogs' => $blogs
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BlogForm');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'position' => 'nullable|integer|min:0',
            'status' => 'required|in:Draft,Published',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title);
        
        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $count = 1;
        while (Blog::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('blogs.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/BlogForm', [
            'blog' => $blog
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'position' => 'nullable|integer|min:0',
            'status' => 'required|in:Draft,Published',
        ]);

        $data = $request->except('image');
        
        if ($request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title);
            $originalSlug = $data['slug'];
            $count = 1;
            while (Blog::where('slug', $data['slug'])->where('id', '!=', $blog->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        if ($request->hasFile('image')) {
            if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
                Storage::disk('public')->delete($blog->image_path);
            }
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('blogs.index')->with('success', 'Bài viết đã được cập nhật.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
            Storage::disk('public')->delete($blog->image_path);
        }
        
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Đã xóa bài viết.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs/content', 'public');
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }
}
