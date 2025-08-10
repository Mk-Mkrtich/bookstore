<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{

    public function boot(Schedule $schedule)
    {
        echo "ScheduleServiceProvider booted\n";

        $schedule->command('app:expire-reservations')->everyMinute();
    }
}
