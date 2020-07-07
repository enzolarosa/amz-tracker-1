<?php

namespace App\Providers;

use App\Charts\DailyUsersChart;
use App\Models\AmzProduct;
use App\Observers\AmzProductObserver;
use ConsoleTVs\Charts\Registrar;
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
    }

    protected function charts()
    {
        app(Registrar::class)->register([
            DailyUsersChart::class,
        ]);
    }
}
