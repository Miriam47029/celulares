<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CelularesController;  
use App\Http\Controllers\MarcaController;   
use App\Http\Controllers\AuthController;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('marcas', MarcaController::class);
Route::resource('celulares',CelularesController::class);
Route::get('celularesall', [CelularesController::class, 'all']);
Route::get('celularesbymarca', [CelularesController::class, 'CelularesByMarca']);
Route::get('auth/logout', [AuthController::class, 'logout']);
});