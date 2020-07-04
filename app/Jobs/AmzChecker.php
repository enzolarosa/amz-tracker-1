<?php

namespace App\Jobs;

use App\Crawler\AmazonItObserver;
use App\Models\PriceTrace;
use Exception;
use Spatie\Crawler\Crawler;

class AmzChecker extends Job
{
    protected PriceTrace $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('amz-checker');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $observer = $this->getObserver();
        $observer->setProduct($this->getProduct());

        Crawler::create()
            ->setCrawlObserver($observer)
            ->ignoreRobots()
            ->setMaximumCrawlCount(1)
            ->startCrawling($this->getProductUrl());
    }

    /**
     * @return AmazonItObserver
     * @throws Exception
     */
    protected function getObserver()
    {
        switch ($this->getProduct()->store) {
            case 'IT':
                return new AmazonItObserver();
            default:
                throw new Exception('Not supported');
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getProductUrl(): string
    {
        switch ($this->getProduct()->store) {
            case 'IT':
                return "https://www.amazon.it/dp/" . $this->getProduct()->product_id;
            default:
                throw new Exception('Not supported');
        }
    }

    /**
     * @return PriceTrace
     */
    public function getProduct(): PriceTrace
    {
        return $this->product;
    }

    /**
     * @param PriceTrace $product
     */
    public function setProduct(PriceTrace $product): void
    {
        $this->product = $product;
    }
}
