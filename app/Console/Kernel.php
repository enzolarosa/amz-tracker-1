<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Telegram\Bot\Laravel\Artisan\WebhookCommand;
use App\Console\Commands\DispatchAmzCheckerCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DispatchAmzCheckerCommand::class,
   //     WebhookCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->hourly($schedule);
        $this->daily($schedule);
        $this->weekly($schedule);
        $this->monthly($schedule);
        $this->yearly($schedule);
    }

    public function hourly(Schedule $schedule)
    {
        $schedule->command('amz:check')->everyMinute();
    }

    public function daily(Schedule $schedule)
    {
        $schedule->command(' art telegram:webhook', [
            'amztracker',
            '--setup',
        ])->dailyAt('02:00');
    }

    public function weekly(Schedule $schedule)
    {
    }

    public function monthly(Schedule $schedule)
    {
    }

    public function yearly(Schedule $schedule)
    {
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
