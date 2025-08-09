<?php

namespace App\Console\Commands;

use App\Service\ReservationService;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Expired Reservations';

    /**
     * Execute the console command.
     */
    public function handle(ReservationService $service)
    {
        $count = $service->expirePendingReservations();
        $this->info("Expired {$count} reservations.");
        return 0;
    }
}
