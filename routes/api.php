<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\createHotelController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post('/login', AuthController::class);
Route::get('/logout', LogoutController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/hotel', createHotelController::class);
});
