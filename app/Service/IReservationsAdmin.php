<?php

namespace App\Service;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

interface IReservationsAdmin
{

    public function confirmReservation(int $reservationId): Reservation;

    public function cancelReservation(int $reservationId): Reservation;

    public function getPendingReservations(): Collection;


}
