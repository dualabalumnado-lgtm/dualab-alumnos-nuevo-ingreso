<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;

/*
|--------------------------------------------------------------------------
| Usuario autenticado
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Ruta de prueba backend
|--------------------------------------------------------------------------
*/

Route::get('/datos', function () {
    return response()->json([
        'message' => '¡Hola desde Laravel API! 🎉',
        'timestamp' => now()
    ]);
});

/*
|--------------------------------------------------------------------------
| Test API proyecto
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API Dualab-alumnos funcionando'
    ]);
});

/*
|----------
| Mentor virtual
|*/

Route::post('/mentor', [MentorController::class, 'chat']);