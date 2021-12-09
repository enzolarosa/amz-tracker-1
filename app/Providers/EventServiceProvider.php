<?php

namespace App\Providers;

use App\Events\Common\WriteLogEvent;
use App\Events\ProductPriceChangedEvent;
use App\Listeners\Common\WriteLogListener;
use App\Listeners\ProductPriceChangedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Common
        WriteLogEvent::class => [
            WriteLogListener::class,
        ],

        // Product Price changed
        ProductPriceChangedEvent::class => [
            ProductPriceChangedListener::class,
        ]
    ];
}
