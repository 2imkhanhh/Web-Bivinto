<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/ve-chung-toi', function () {
    return view('about-us');
});