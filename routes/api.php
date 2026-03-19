<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/datos', function () {
    return response()->json([
        'message' => '¡Hola desde Laravel API! ',
        'timestamp' => now()
    ]);
});


Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store']);
Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);