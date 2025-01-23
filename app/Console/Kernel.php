<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        Log::info('Crone Started Successfully!.');
        $schedule->command('app:analytics-command')->everyMinute();
        $schedule->command('app:client-command')->everyMinute();
        $schedule->command('app:session-command')->everyMinute();
        Log::info('Crone Completed Successfully!.');

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
