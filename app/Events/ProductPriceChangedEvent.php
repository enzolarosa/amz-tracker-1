<?php

namespace App\Events;

use App\Models\AmzProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPriceChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected AmzProduct $product;
    protected $previous_price;

    /**
     * @return mixed
     */
    public function getPreviousPrice()
    {
        return $this->previous_price;
    }

    /**
     * @param mixed $previous_price
     */
    public function setPreviousPrice($previous_price): void
    {
        $this->previous_price = $previous_price;
    }

    /**
     * @return AmzProduct
     */
    public function getProduct(): AmzProduct
    {
        return $this->product;
    }

    /**
     * @param AmzProduct $product
     */
    public function setProduct(AmzProduct $product): void
    {
        $this->product = $product;
    }
}
