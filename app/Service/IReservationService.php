<?php

namespace App\Service;

use App\Models\Reservation;

interface IReservationService
{

    public function createReservation(int $userId, int $bookId, int $quantity): Reservation;
}
