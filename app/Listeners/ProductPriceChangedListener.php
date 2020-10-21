<?php

namespace App\Listeners;

use App\Events\ProductPriceChangedEvent;
use App\Jobs\ProductPriceChangedJob;
use App\Models\AmzProduct;
use App\Models\Notification;
use App\Models\User;

class ProductPriceChangedListener
{
    /**
     * Handle the event.
     *
     * @param ProductPriceChangedEvent $event
     * @return void
     */
    public function handle(ProductPriceChangedEvent $event)
    {
        ProductPriceChangedJob::dispatch($event->getProduct())->delay(now()->addMinute());
    }
}
