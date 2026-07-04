<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home']);
Route::get('/ve-chung-toi', [PageController::class, 'aboutUs']);
Route::get('/san-pham', [PageController::class, 'products']);
Route::get('/chi-tiet-san-pham', [PageController::class, 'productDetail']);
Route::get('/hop-tac', [PageController::class, 'collaboration']);
Route::get('/chinh-sach', [PageController::class, 'policy']);
Route::get('/blogs', [PageController::class, 'blogs']);
Route::get('/gio-hang', [PageController::class, 'cart']);

Route::get('/tai-khoan', function () {
    return view('auth');
});

Route::get('/ho-so', function () {
    return view('profile');
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', App\Http\Controllers\Api\ProductController::class);
});