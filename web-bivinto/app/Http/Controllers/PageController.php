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
            
        return view('home', compact('categories', 'featuredProducts'));
    }

    public function aboutUs()
    {
        return view('about-us');
    }

    public function products()
    {
        $categories = Category::where('status', 'active')
            ->whereNull('parent_id')
            ->orderBy('display_order', 'asc')
            ->get();
            
        return view('products', compact('categories'));
    }

    public function productDetail()
    {
        return view('product-detail');
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
        return view('blogs');
    }

    public function cart()
    {
        return view('cart');
    }
}
