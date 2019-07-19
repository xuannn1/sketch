<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
    * The Artisan commands provided by your application.
    *
    * @var array
    */
    protected $commands = [
        \App\Console\Commands\CountWebStat::class,
    ];

    /**
    * Define the application's command schedule.
    *
    * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
    * @return void
    */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('webstat:count')
        ->name('webstat:count')
        ->daily()
        ->onOneServer();
        $schedule->command('cache:clear')
        ->name('cache:clear')
        ->timezone('Asia/Shanghai')
        ->dailyAt('4:00')
        ->withoutOverlapping(10)
        ->onOneServer();
        $schedule->command('testlog:send')
        ->name('testlog:send')
        ->hourly()
        ->onOneServer();
        // $schedule->command('activation:promote')
        // ->name('token:refresh')
        // ->timezone('Asia/Shanghai')
        // ->dailyAt('4:30')
        // ->onOneServer();
    }

    /**
    * Register the Closure based commands for the application.
    *
    * @return void
    */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
