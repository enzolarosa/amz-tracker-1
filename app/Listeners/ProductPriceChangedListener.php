<?php

namespace App\Listeners;

use App\Events\ProductPriceChangedEvent;
use App\Models\AmzProduct;
use App\Models\User;
use App\Notifications\ProductPriceChangedNotification;

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
        $prod = $event->getProduct();
        /** @var User $users */
        $users = $prod->users()->where('active', true)->get();
        dump($users->count(),'users');
        $users->each(function (User $user) use ($prod) {
            dump("can notify?");
            if ($this->shouldNotify($user, $prod)) {
                $notification = new ProductPriceChangedNotification();
                $notification->setProduct($prod);
                $user->notify($notification);
            }
        });
    }

    protected function shouldNotify(User $user, AmzProduct $product): bool
    {
        // TODO add user custom logic
        return true;
    }
}
