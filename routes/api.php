<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReservationAdminController;
use App\Http\Controllers\API\ReservationController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('reservations/me', [ReservationController::class, 'myReservations']);
    Route::post('reservations', [ReservationController::class, 'store']);

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('reservations', [ReservationAdminController::class, 'index']);
        Route::patch('reservations/{resId}/confirm', [ReservationAdminController::class, 'confirm']);
        Route::patch('reservations/{resId}/cancel', [ReservationAdminController::class, 'cancel']);
    });
});
