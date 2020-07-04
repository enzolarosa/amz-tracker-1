<?php

namespace App\Providers;

use App\Events\PriceLogEvent;
use App\Listeners\PriceLogListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PriceLogEvent::class => [
            PriceLogListener::class
        ],
    ];
}
