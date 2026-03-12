<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/datos', function () {
    return response()->json([
        'message' => '¡Hola desde Laravel API! 🎉',
        'timestamp' => now()
    ]);
});