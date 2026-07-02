<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/ve-chung-toi', function () {
    return view('about-us');
});

Route::get('/san-pham', function () {
    return view('products');
});

Route::get('/chi-tiet-san-pham', function () {
    return view('product-detail');
});

Route::get('/hop-tac', function () {
    return view('collaboration');
});