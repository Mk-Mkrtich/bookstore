<?php

namespace App\Service;

interface IReservationsExpired
{
    public function expirePendingReservations(): int;
}
