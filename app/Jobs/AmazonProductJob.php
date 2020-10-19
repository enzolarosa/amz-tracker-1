<?php

namespace App\Jobs;

use App\Common\Constants;
use App\Events\ProductPriceChangedEvent;
use App\Jobs\Amazon\ProductDetailsJob;
use App\Jobs\Amazon\ProductOffersJob;
use App\Models\AmzProduct;
use App\Models\AmzProductQueue;
use Illuminate\Bus\Batch;
use Throwable;

class AmazonProductJob extends Job
{
    protected string $asin;
    protected array $countries;

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'asin:' . $this->asin];
    }

    /**
     * Create a new job instance.
     *
     * @param string $asin
     * @param array $countries
     */
    public function __construct(string $asin, array $countries = ['IT'])
    {
        $this->onQueue('amz-product');

        $this->asin = $asin;
        $this->countries = $countries;

    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->asin]);
        AmzProductQueue::query()->firstOrCreate(['amz_product_id' => $prod->id, 'reserved_at' => now()]);

        $details = new ProductDetailsJob($this->asin);
        $offers = new ProductOffersJob($this->asin);

        $batch = \Bus::batch([
            $details,
            $offers
        ])->then(function (Batch $batch) use ($prod) {
            if ($prod->wasChanged('current_price') && $prod->current_price < $prod->preview_price) {
                $event = new ProductPriceChangedEvent();
                $event->setProduct($prod);
                event($event);
            }
        })->onQueue('check-amz-product')->name("Check `$prod->asin` amazon product")->dispatch();

    }
}
