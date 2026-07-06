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
    return view('auth');
})->name('login');

Route::get('/ho-so', function () {
    return view('profile');
});

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/gio-hang', [PageController::class, 'cart']);

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add']);
    Route::put('/cart/{id}', [App\Http\Controllers\CartController::class, 'update']);
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'remove']);
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', function () {
        return Inertia\Inertia::render('Admin/Dashboard');
    });
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
});