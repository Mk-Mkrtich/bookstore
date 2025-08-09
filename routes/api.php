<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('reservations/me', [ReservationController::class, 'myReservations']);
    Route::post('reservations', [ReservationController::class, 'store']);
});
