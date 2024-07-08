<?php

namespace App\Console;

use App\Console\Commands\StoreBitcoinQuoteCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        StoreBitcoinQuoteCron::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        Log::info('kernel schedule running by cron...');

        $schedule->command('bitcoin:store-quote')->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
