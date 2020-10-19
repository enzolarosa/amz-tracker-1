<?php

namespace App\Listeners;

use App\Events\ProductPriceChangedEvent;
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
        $prod = $event->getProduct();
        /** @var User $users */
        $users = $prod->users()->where('active', true)->get();
        $users->each(function (User $user) use ($prod) {
            if ($this->shouldNotify($user, $prod)) {
                Notification::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'amz_product_id' => $prod->id,
                    'sent' => false,
                    'price' => $prod->current_price,
                ]);
            }
        });
    }

    protected function shouldNotify(User $user, AmzProduct $product): bool
    {
        // TODO add user custom logic
        return true;
    }
}
