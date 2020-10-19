<?php

namespace App\Jobs;

use App\Jobs\Amazon\ProductDetailsJob;
use App\Jobs\Amazon\ProductOffersJob;
use App\Models\AmzProduct;
use App\Models\AmzProductQueue;
use Illuminate\Support\Facades\Bus;
use Throwable;

class AmazonProductJob extends Job
{
    protected string $asin;
    protected array $countries;
    protected $batchUuid;

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
     * @param null $batchUuid
     */
    public function __construct(string $asin, $batchUuid = null, array $countries = ['IT'])
    {
        $this->onQueue('amz-product');

        $this->asin = $asin;
        $this->countries = $countries;
        $this->batchUuid = $batchUuid;
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

        $details = new ProductDetailsJob($this->asin, $this->batchUuid);
        $offers = new ProductOffersJob($this->asin, $this->batchUuid);

        $batch = Bus::batch([])->name("Check `$prod->asin` amazon product")->dispatch();
        if ($this->batchUuid) {
            $batch = Bus::findBatch($this->batchId);
        }

        $batch->add([$details, $offers]);
    }
}
