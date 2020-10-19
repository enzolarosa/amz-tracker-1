<?php

namespace App\Observers;

use App\Events\ProductPriceChangedEvent;
use App\Models\AmzProduct;
use App\Models\AmzProductLog;
use App\Models\Notification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;

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
        if (!is_null($product->sellers) && $product->sellers->count() > 0) {
            $product->current_price = $this->minPrice($product->sellers);
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
        if ($product->wasChanged('current_price') && $product->current_price < $product->preview_price) {
            $event = new ProductPriceChangedEvent();
            $event->setProduct($product);
            event($event);
        }

        AmzProductLog::query()->create([
            'amz_product_id' => $product->id,
            'history' => $product->toArray(),
        ]);
    }

    /**
     * @param Collection $sellers
     * @return float
     */
    protected function minPrice(Collection $sellers): float
    {
        $min = 0;
        $sellers->each(function ($seller, $index) use (&$min) {
            if ($index == 0 ||
                ($seller['priceParsed'] < $min && in_array(strtolower($seller['condition']), ['nuovo', 'new']))) {
                $min = $seller['priceParsed'];
                return;
            }
        });
        return $min;
    }
}
