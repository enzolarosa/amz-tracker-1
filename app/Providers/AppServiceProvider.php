<?php

namespace App\Providers;

use App\Models\AmzProduct;
use App\Models\Setting;
use App\Observers\AmzProductObserver;
use App\Observers\SettingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
     /*   app(Registrar::class)->register([
            DailyUsersChart::class,
            DailyProductsChart::class,
            DailyProductAnalyzeChart::class,
        ]);*/
    }
}
