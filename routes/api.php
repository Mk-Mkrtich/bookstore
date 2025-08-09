<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::get('reservations/me', [ReservationController::class, 'myReservations']);
});
