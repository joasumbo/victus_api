<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BannerController;
use App\Http\Controllers\FraseController;
use App\Http\Controllers\PesoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->withoutMiddleware('auth:api');
Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'token' => $token
    ]);
})->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->withoutMiddleware('auth:api');


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me/update', [AuthController::class, 'updateProfile']);

    // Libraries
    Route::get('/libraries', [LibraryController::class, 'index']);
    Route::post('/libraries', [LibraryController::class, 'store']);
    Route::get('/libraries/{id}', [LibraryController::class, 'show']);
    Route::delete('/libraries/{id}', [LibraryController::class, 'destroy']);
    // Videos
    Route::get('/videos', [VideoController::class, 'index']);
    Route::post('/videos', [VideoController::class, 'store']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

    // Banners
    Route::get('/banners', [BannerController::class, 'index']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::get('/banners/{id}', [BannerController::class, 'show']);
    Route::post('/banners/{id}', [BannerController::class, 'update']);
    Route::delete('/banners/{id}', [BannerController::class, 'destroy']);

    // Frases
    Route::get('/frases', [FraseController::class, 'index']);
    Route::post('/frases', [FraseController::class, 'store']);
    Route::get('/frases/last', [FraseController::class, 'last']);
    Route::get('/frases/{id}', [FraseController::class, 'show']);
    Route::put('/frases/{id}', [FraseController::class, 'update']);
    Route::delete('/frases/{id}', [FraseController::class, 'destroy']);

    // Pesos
    Route::get('/pesos', [PesoController::class, 'index']);
    Route::post('/pesos', [PesoController::class, 'store']);
    Route::get('/show-pesos', [PesoController::class, 'show']);
    Route::put('/pesos/{id}', [PesoController::class, 'update']);
    Route::delete('/pesos/{id}', [PesoController::class, 'destroy']);

    // Eventos
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/upcoming', [EventoController::class, 'upcoming']);
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos/{id}', [EventoController::class, 'show']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);
});
