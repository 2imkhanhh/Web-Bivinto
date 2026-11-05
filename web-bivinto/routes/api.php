<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });
});
