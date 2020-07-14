<?php

namespace App\Console;

use aglipanci\ForgeTile\Commands\FetchForgeRecentEventsCommand;
use aglipanci\ForgeTile\Commands\FetchForgeServersCommand;
use App\Console\Commands\DispatchAmzCheckerCommand;
use App\Console\Commands\SearchProductCommand;
use App\Console\Commands\UpdateProductCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use MarcusMyers\AccuWeatherTile\Commands\FetchAccuWeatherCurrentConditionsCommand;
use MarcusMyers\AccuWeatherTile\Commands\FetchAccuWeatherFiveDayForecastCommand;

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
        SearchProductCommand::class,
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
        $schedule->command('amz:update-product')->everyMinute();
    }

    public function daily(Schedule $schedule)
    {
        $schedule->command('telegram:webhook', ['amztracker', '--setup'])->dailyAt('02:00');
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
        $schedule->command(FetchForgeRecentEventsCommand::class)->everyMinute();

        $schedule->command(FetchAccuWeatherCurrentConditionsCommand::class)->hourly();
        $schedule->command(FetchForgeServersCommand::class)->hourly();

        $schedule->command(FetchAccuWeatherFiveDayForecastCommand::class)->daily();
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
