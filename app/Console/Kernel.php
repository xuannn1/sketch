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
        $schedule->command('data:recalculation')
        ->name('data:recalculation')
        ->hourly()
        ->onOneServer();
        //->everyFiveMinutes();
        $schedule->command('cache:clear')
        ->name('cache:clear')
        ->daily()
        ->timezone('Asia/Shanghai')
        ->between('3:00', '5:00')
        ->withoutOverlapping(10)
        ->onOneServer();
        $schedule->command('testlog:send')
        ->name('testlog:send')
        ->hourly()
        ->onOneServer();
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
