<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutReservationController;
use App\Http\Controllers\createHotelController;
use App\Http\Controllers\CreateRoomController;
use App\Http\Controllers\CreateRoomReservationController;
use App\Http\Controllers\DeleteRoomController;
use App\Http\Controllers\EditHotelController;
use App\Http\Controllers\EditRoomController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RemoveHotelController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', AuthController::class);
Route::get('/logout', LogoutController::class);
Route::post('/teste', [StripeController::class, 'processPayment']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/hotel', createHotelController::class);
    Route::delete('/hotel/{id}', RemoveHotelController::class);
    Route::put('/hotel/{id}', EditHotelController::class);

    Route::post('/room', CreateRoomController::class);
    Route::put('/room/{id}', EditRoomController::class);
    Route::delete('/room/{id}', DeleteRoomController::class);

    Route::post('/rooms/reservations', CreateRoomReservationController::class);
    Route::post('/rooms/checkout', CheckoutReservationController::class);
});
