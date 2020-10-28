<?php

namespace App\Providers;

use App\Charts\DailyProductAnalyzeChart;
use App\Charts\DailyProductsChart;
use App\Charts\DailyUsersChart;
use App\Models\AmzProduct;
use App\Models\Setting;
use App\Observers\AmzProductObserver;
use App\Observers\SettingObserver;
use ConsoleTVs\Charts\Registrar;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->modelObserver();
        $this->charts();
    }

    protected function modelObserver(): void
    {
        AmzProduct::observe(AmzProductObserver::class);
        Setting::observe(SettingObserver::class);
    }

    protected function charts()
    {
        app(Registrar::class)->register([
            DailyUsersChart::class,
            DailyProductsChart::class,
            DailyProductAnalyzeChart::class,
        ]);
    }
}
