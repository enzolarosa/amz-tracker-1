<?php

namespace App\Console;

use App\Console\Commands\UpdateWishlistCommand;
use App\Console\Commands\CleanUpSettingCommand;
use App\Console\Commands\DispatchAmzCheckerCommand;
use App\Console\Commands\ProcessNotificationCommand;
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
        UpdateWishlistCommand::class,
        SearchProductCommand::class,
        ProcessNotificationCommand::class,
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
        $schedule->command(CleanUpSettingCommand::class)->everyMinute();

        $schedule->command(ProcessNotificationCommand::class)->withoutOverlapping()->everyMinute();
        $schedule->command('server-monitor:run-checks')->withoutOverlapping()->everyMinute();

        // is need a lot of listener in order to prevent the 503 amz error
        //  $schedule->command(UpdateProductCommand::class)->withoutOverlapping()->everyMinute();

        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    public function daily(Schedule $schedule)
    {
        $schedule->command('telegram:webhook', ['amztracker', '--setup'])->dailyAt('02:00');
        $schedule->command(UpdateWishlistCommand::class)->cron('15 */4 * * *'); // @link https://crontab.guru/#15_*/4_*_*_*
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
        //    $schedule->command(FetchPingPingMonitorsCommand::class)->withoutOverlapping()->everyMinute();
        //    $schedule->command(FetchForgeRecentEventsCommand::class)->withoutOverlapping()->everyMinute();
        //    $schedule->command(FetchForgeServersCommand::class)->withoutOverlapping()->hourly();

        $schedule->command(FetchAccuWeatherCurrentConditionsCommand::class)->withoutOverlapping()->hourly();
        $schedule->command(FetchAccuWeatherFiveDayForecastCommand::class)->withoutOverlapping()->daily();
    }
}
