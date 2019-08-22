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
        $schedule->command('testlog:send')
        ->name('testlog:send')
        ->hourly()
        ->onOneServer();

        $schedule->command('activation:promote')
        ->name('activation:promote')
        ->timezone('Asia/Shanghai')
        ->dailyAt('4:00')
        ->onOneServer();

        $schedule->command('data:rewardDonationRecords')
        ->name('data:rewardDonationRecords')
        ->timezone('Asia/Shanghai')
        ->dailyAt('4:10')
        ->onOneServer();

        $schedule->command('data:recalculation')
        ->name('data:recalculation')
        ->timezone('Asia/Shanghai')
        ->dailyAt('4:15')
        ->onOneServer();

        $schedule->command('data:archiveRecords')
        ->name('data:archiveRecords')
        ->timezone('Asia/Shanghai')
        ->dailyAt('4:30')
        ->onOneServer();

        $schedule->command('token:refresh')
        ->name('token:refresh')
        ->timezone('Asia/Shanghai')
        ->weeklyOn(3, '4:45')
        ->onOneServer();

        $schedule->command('webstat:count')
        ->name('webstat:count')
        ->daily()
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
