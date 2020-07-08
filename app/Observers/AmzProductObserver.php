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
        $this->onSaving($product);
    }

    /**
     * Handle the file sequence "updating" event.
     *
     * @param AmzProduct $product
     *
     * @return void
     */
    public function updating(AmzProduct $product)
    {
        $this->onSaving($product);
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
        $this->onSaved($product);
    }

    /**
     * Handle the file sequence "updated" event.
     *
     * @param AmzProduct $product
     *
     * @return void
     */
    public function updated(AmzProduct $product)
    {
        $this->onSaved($product);
    }

    /**
     * @param AmzProduct $product
     */
    protected function onSaving(AmzProduct $product)
    {
        dump("sellers changed? " . $product->isDirty('sellers'));
        if ($product->isDirty('sellers') && !is_null($product->sellers) && $product->sellers->count() > 0) {
            $product->current_price = Arr::first($product->sellers)['priceParsed']; // get the minium price
        }

        if (is_null($product->start_price) && !is_null($product->sellers) && $product->sellers->count() > 0) {
            $product->start_price = $product->current_price;
        }
    }

    /**
     * @param AmzProduct $product
     */
    protected function onSaved(AmzProduct $product)
    {
        dump("notify?" .
            $product->wasChanged('current_price') && $product->current_price < $product->preview_price,
            $product->current_price, $product->preview_price,
            "current price changed? " . $product->wasChanged('current_price')
        );

        if ($product->wasChanged('current_price') && $product->current_price < $product->preview_price) {
            dump('changes', $product->getChanges());
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
