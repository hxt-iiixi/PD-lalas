<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendDailySalesEmail;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendDailySalesEmail::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:daily-sales')->dailyAt('00:00'); // 9 PM
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
