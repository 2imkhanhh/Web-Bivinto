<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class PageController extends Controller
{
    public function home()
    {
        $categories = Category::where('status', 'active')
            ->whereNull('parent_id')
            ->where('is_featured', true)
            ->orderBy('display_order', 'asc')
            ->take(6)
            ->get();

        $featuredProducts = \App\Models\Product::with(['images'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->get();

        $blogs = \App\Models\Blog::where('status', 'Published')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'blogs'));
    }

    public function aboutUs()
    {
        return view('about-us');
    }

    public function products(Request $request)
    {
        $categories = Category::where('status', 'active')
            ->whereNull('parent_id')
            ->orderBy('display_order', 'asc')
            ->get();

        $query = \App\Models\Product::with(['images', 'colors.sizes'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');

        if ($request->has('category') && $request->category !== 'all' && $request->category !== '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('size') && $request->size !== 'all' && $request->size !== '') {
            $query->whereHas('colors.sizes', function ($q) use ($request) {
                $q->where('size_name', $request->size);
            });
        }

        if ($request->has('color') && $request->color !== 'all' && $request->color !== '') {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('color_name', $request->color);
            });
        }

        if ($request->has('price') && $request->price !== 'all' && $request->price !== '') {
            $price = $request->price;
            if ($price === 'under_500') {
                $query->where('price', '<', 500000);
            } elseif ($price === '500_1000') {
                $query->whereBetween('price', [500000, 1000000]);
            } elseif ($price === 'over_1000') {
                $query->where('price', '>', 1000000);
            }
        }

        $products = $query->paginate(16);

        if ($request->ajax()) {
            $html = view('partials.product_list', compact('products'))->render();
            return response()->json([
                'html' => $html,
                'hasMore' => $products->hasMorePages()
            ]);
        }

        $allSizes = \Illuminate\Support\Facades\DB::table('product_sizes')
            ->join('product_colors', 'product_colors.id', '=', 'product_sizes.product_color_id')
            ->join('products', 'products.id', '=', 'product_colors.product_id')
            ->where('products.status', 'active')
            ->select('product_sizes.size_name')
            ->distinct()
            ->pluck('size_name')
            ->toArray();

        $allColors = \Illuminate\Support\Facades\DB::table('product_colors')
            ->join('products', 'products.id', '=', 'product_colors.product_id')
            ->where('products.status', 'active')
            ->select('product_colors.color_name')
            ->distinct()
            ->pluck('color_name')
            ->toArray();

        // Sắp xếp lại danh sách size nếu cần (S, M, L, XL, XXL)
        $sizeOrder = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6];
        usort($allSizes, function ($a, $b) use ($sizeOrder) {
            $posA = $sizeOrder[$a] ?? 99;
            $posB = $sizeOrder[$b] ?? 99;
            return $posA <=> $posB;
        });

        return view('products', compact('categories', 'products', 'allSizes', 'allColors'));
    }

    public function productDetail($slug)
    {
        $product = \App\Models\Product::with(['colors.sizes', 'colors.images', 'category'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $similarProducts = \App\Models\Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(8)
            ->get();

        return view('product-detail', compact('product', 'similarProducts'));
    }

    public function collaboration()
    {
        return view('collaboration');
    }

    public function policy()
    {
        return view('policy');
    }

    public function blogs()
    {
        $featuredBlogs = \App\Models\Blog::where('status', 'Published')
            ->where('is_featured', true)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $latestBlogs = \App\Models\Blog::where('status', 'Published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('blogs', compact('featuredBlogs', 'latestBlogs'));
    }

    public function blogDetail($slug)
    {
        $blog = \App\Models\Blog::where('slug', $slug)
            ->where('status', 'Published')
            ->firstOrFail();

        $relatedBlogs = \App\Models\Blog::where('status', 'Published')
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('blog-detail', compact('blog', 'relatedBlogs'));
    }

    public function cart()
    {
        if (auth()->check()) {
            $cartItems = \App\Models\Cart::with(['product', 'color', 'size'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $cartItems = \App\Models\Cart::with(['product', 'color', 'size'])
                ->where('guest_cart_token', \Illuminate\Support\Facades\Cookie::get('guest_cart_token'))
                ->get();
        }

        return view('cart', compact('cartItems'));
    }

    public function checkout(\Illuminate\Http\Request $request)
    {
        if (!$request->has('items') || empty($request->items)) {
            return redirect('/gio-hang');
        }

        $itemIds = explode(',', $request->items);

        $query = \App\Models\Cart::with(['product', 'color', 'size']);
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('guest_cart_token', \Illuminate\Support\Facades\Cookie::get('guest_cart_token'));
        }

        $cartItems = $query->whereIn('id', $itemIds)->get();

        if ($cartItems->isEmpty()) {
            return redirect('/gio-hang');
        }

        return view('checkout', compact('cartItems'));
    }
}