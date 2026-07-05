<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Các API route yêu cầu session thay vì JWT (cho user profile v.v)
Route::middleware(['auth'])->group(function () {
    Route::get('/me', function(Request $request) {
        return response()->json($request->user());
    });
    // Add updateProfile here if needed, or move to web routes.
});
