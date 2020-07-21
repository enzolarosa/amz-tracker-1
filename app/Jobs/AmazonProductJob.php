<?php

namespace App\Jobs;

use App\Jobs\Amazon\ProductDetailsJob;
use App\Jobs\Amazon\ProductOffersJob;
use App\Models\AmzProduct;
use App\Models\AmzProductQueue;

class AmazonProductJob extends Job
{
    const WAIT_CRAWLER = 30;

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
     */
    public function handle()
    {
        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->asin]);
        AmzProductQueue::query()->firstOrCreate(['amz_product_id' => $prod->id, 'reserved_at' => now()]);

        if ($this->needDetails($prod)) {
            $details = new ProductDetailsJob($this->asin);
            dispatch($details);
        }

        $offers = new ProductOffersJob($this->asin);
        dispatch($offers)->delay(now()->addSeconds(self::WAIT_CRAWLER));

    }

    /**
     * @param AmzProduct $product
     * @return bool
     */
    protected function needDetails(AmzProduct $product): bool
    {
        return is_null($product->title) || $product->created_at <= now()->subWeek();
    }
}
