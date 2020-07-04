<?php

namespace App\Console;

use App\Console\Commands\DispatchAmzCheckerCommand;
use App\Console\Commands\TelegramBotCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Laravelista\LumenVendorPublish\VendorPublishCommand;
use Telegram\Bot\Laravel\Artisan\WebhookCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        VendorPublishCommand::class,

        DispatchAmzCheckerCommand::class,
        WebhookCommand::class,
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
}
