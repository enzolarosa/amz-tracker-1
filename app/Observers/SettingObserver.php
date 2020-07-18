<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Str;

class SettingObserver
{
    use DispatchesJobs;

    /**
     * Handle the file sequence "creating" event.
     *
     * @param Setting $setting
     *
     * @return void
     */
    public function creating(Setting $setting)
    {
        if (is_null($setting->id)) {
            $setting->id = Str::uuid();
        }
    }

}
