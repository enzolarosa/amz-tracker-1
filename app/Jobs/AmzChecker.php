<?php

namespace App\Jobs;

use App\Crawler\AmazonItObserver;
use App\Models\PriceTrace;
use Carbon\Carbon;
use DateTime;
use Exception;
use Spatie\Crawler\Crawler;
use Spatie\RateLimitedMiddleware\RateLimited;

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

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(3)
            ->everySeconds(10)
            ->releaseAfterSeconds(20);

        return [$rateLimitedMiddleware];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return Carbon::now()->addDay();
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
