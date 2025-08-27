<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);


    Route::get('/libraries', [LibraryController::class, 'index']); 
    Route::post('/libraries', [LibraryController::class, 'store']); 
    Route::get('/libraries/{id}', [LibraryController::class, 'show']);
    Route::delete('/libraries/{id}', [LibraryController::class, 'destroy']);

    Route::get('/videos', [VideoController::class, 'index']); 
    Route::post('/videos', [VideoController::class, 'store']); 
    Route::get('/videos/{id}', [VideoController::class, 'show']); 
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']); 

});
