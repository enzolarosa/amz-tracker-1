<?php

namespace App\Providers;

use App\Events\Common\QueriesLoggerEvent;
use App\Listeners\Auth\LogVerifiedUser;
use App\Listeners\Common\QueriesLoggerListener;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class => [
            LogVerifiedUser::class,
        ],
        PasswordReset::class => [

        ],
        QueriesLoggerEvent::class => [
            QueriesLoggerListener::class,
        ]
    ];

    public function boot()
    {
        //
    }
}
