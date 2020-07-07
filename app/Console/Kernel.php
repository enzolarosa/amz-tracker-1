<?php

namespace App\Console;

use aglipanci\ForgeTile\Commands\FetchForgeRecentEventsCommand;
use aglipanci\ForgeTile\Commands\FetchForgeServersCommand;
use App\Console\Commands\DispatchAmzCheckerCommand;
use App\Console\Commands\UpdateProductCommand;
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
        DispatchAmzCheckerCommand::class,
        UpdateProductCommand::class,
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

        $this->dashboard($schedule);
    }

    public function hourly(Schedule $schedule)
    {
        $schedule->command('amz:update-product')->everyTenMinutes();
    }

    public function daily(Schedule $schedule)
    {
        $schedule->command(' art telegram:webhook', ['amztracker', '--setup'])->dailyAt('02:00');
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

    public function dashboard(Schedule $schedule)
    {
        $schedule->command(\MarcusMyers\AccuWeatherTile\Commands\FetchAccuWeatherCurrentConditionsCommand::class)->hourly();
        $schedule->command(\MarcusMyers\AccuWeatherTile\Commands\FetchAccuWeatherFiveDayForecastCommand::class)->daily();

        $schedule->command(FetchForgeServersCommand::class)->hourly();
        $schedule->command(FetchForgeRecentEventsCommand::class)->everyMinute();
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
