<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        //        Commands\StatusReport::class,
        //        Commands\Dublicate::class,
        //        Commands\BankOtkrytie::class,
        Commands\Test::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // каждые пять минут

        //получение городов и тарифов банк открытие
        // $schedule->command('otkrytie:cron')->everyFiveMinutes();
        // $schedule->command('otkrytie:cron')->dailyAt('08:00');

        // проверка статуса отправленных заявок
        // $schedule->command('statusreport:cron')->dailyAt('07:30');

        // проверка на дубли
        // $schedule->command('duplicate:check')->everyFiveMinutes();

        // тэстовая комманда
//        $schedule->command('test:test')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
