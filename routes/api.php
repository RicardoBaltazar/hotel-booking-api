<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\createHotelController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RemoveHotelController;
use Illuminate\Support\Facades\Route;

Route::post('/login', AuthController::class);
Route::get('/logout', LogoutController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/hotel', createHotelController::class);
    Route::delete('/hotel/{id}', RemoveHotelController::class);
});
