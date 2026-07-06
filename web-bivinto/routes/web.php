<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home']);
Route::get('/ve-chung-toi', [PageController::class, 'aboutUs']);
Route::get('/san-pham', [PageController::class, 'products']);
Route::get('/chi-tiet-san-pham/{slug}', [PageController::class, 'productDetail']);
Route::get('/hop-tac', [PageController::class, 'collaboration']);
Route::get('/chinh-sach', [PageController::class, 'policy']);
Route::get('/blogs', [PageController::class, 'blogs']);
Route::get('/blogs', [PageController::class, 'blogs']);

Route::get('/tai-khoan', function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return view('auth');
})->name('login');



Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/thanh-toan', [PageController::class, 'cart']);

Route::middleware('auth')->group(function () {
    Route::get('/ho-so', function () {
        return view('profile');
    });

    Route::put('/api/profile', [App\Http\Controllers\AuthController::class, 'updateProfile']);

    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add']);
    Route::put('/cart/{id}', [App\Http\Controllers\CartController::class, 'update']);
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'remove']);
    
    Route::post('/dat-hang', [App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/thanh-toan/thanh-cong/{order_code}', [App\Http\Controllers\OrderController::class, 'success']);
    
    Route::get('/don-hang', [App\Http\Controllers\OrderController::class, 'index']);
    Route::get('/don-hang/{orderCode}', [App\Http\Controllers\OrderController::class, 'show']);
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'chartData']);
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    Route::get('/orders/{id}/print', [App\Http\Controllers\Admin\OrderController::class, 'print']);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'update', 'destroy']);
    
    Route::get('/inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index']);
    Route::get('/inventory/history/{productSizeId}', [App\Http\Controllers\Admin\InventoryController::class, 'history']);
    Route::get('/inventory/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'show']);
    Route::put('/inventory/update', [App\Http\Controllers\Admin\InventoryController::class, 'update']);
});