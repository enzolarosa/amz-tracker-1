<?php

namespace App\Listeners;

use App\Events\PriceLogEvent;
use App\Jobs\NotifyJob;
use App\Models\User;
use App\Notifications\PriceLogNotification;

class PriceLogListener
{
    /**
     * Handle the event.
     *
     * @param PriceLogEvent $priceLogEvent
     * @return void
     */
    public function handle(PriceLogEvent $priceLogEvent)
    {
        $users = $priceLogEvent->getProduct()->users;

        $users->each(function (User $user) use ($priceLogEvent) {
            $notification = new PriceLogNotification();
            $notification->setProduct($priceLogEvent->getProduct());

            $user->notify($notification);
        });
    }
}
