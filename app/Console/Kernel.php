<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // comand to set expired at voucher with valid date < actual date
        $schedule->command('gift_vouchers:expired')->daily()->timezone('Europe/Rome');


        $schedule->command('customers:reset-points')
            ->cron('* * * * *') //TODO: reset points at date imposted da testare
            ->timezone('Europe/Rome');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}