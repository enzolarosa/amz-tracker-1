<?php

namespace App\Jobs\Common;

use App\Jobs\Job;
use Illuminate\Support\Facades\Artisan;

class HorizonTerminateJob extends Job
{
    public function __construct()
    {
        $this->onQueue('default');
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['horizon:terminate', get_class($this)];
    }

    public function handle()
    {
        Artisan::call('horizon:terminate');
    }
}
