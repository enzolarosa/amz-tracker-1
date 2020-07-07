<?php

namespace App\Observers;

use App\Events\ProductPriceChangedEvent;
use App\Models\AmzProduct;
use App\Models\AmzProductLog;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;

class AmzProductObserver
{
    use DispatchesJobs;

    /**
     * Handle the file sequence "saving" event.
     *
     * @param AmzProduct $product
     *
     * @return void
     */
    public function saving(AmzProduct $product)
    {
        if (!is_null($product->sellers) && $product->sellers->count() > 0) {
            $product->current_price = Arr::first($product->sellers)['priceParsed'];
        }

        if (is_null($product->start_price) && !is_null($product->sellers) && $product->sellers->count() > 0) {
            $product->start_price = $product->current_price;
        }

        if ($product->preview_price != $product->current_price) {
            $product->preview_price = $product->current_price;
        }
    }

    /**
     * Handle the file sequence "saved" event.
     *
     * @param AmzProduct $product
     *
     * @return void
     */
    public function saved(AmzProduct $product)
    {
        if ($product->current_price < $product->preview_price) {
            dump("notify!");
            $event = new ProductPriceChangedEvent();
            $event->setProduct($product);
            event($event);
        }

        AmzProductLog::query()->create([
            'amz_product_id' => $product->id,
            'history' => $product->toArray(),
        ]);
    }
}
