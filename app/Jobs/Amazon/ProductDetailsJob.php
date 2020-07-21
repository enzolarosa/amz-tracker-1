<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\DetailsCrawler;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class ProductDetailsJob extends Amazon
{
    /**
     * Create a new job instance.
     *
     * @param string $asin
     * @param array $countries
     */
    public function __construct(string $asin, array $countries = ['IT'])
    {
        parent::__construct($asin, $countries);
        $this->onQueue('amz-product-details');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $detailUrl = $this->getProductUrl('detail');
        $shopUrl = $this->getProductUrl('shop');

        while (Setting::read('amz-wait')->value) {
            sleep(10);
        }

        // Get Product Details
        $observer = new DetailsCrawler();
        $observer->setCurrency($this->currency[Arr::first($this->countries)]);
        $observer->setAsin($this->asin);
        $observer->setCountry(Arr::first($this->countries));
        $observer->setShopUrl($shopUrl);

        Crawler::create($this->clientOptions())
            ->ignoreRobots()
            ->acceptNofollowLinks()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->setBrowsershot($this->browsershot())
            ->executeJavaScript()
            ->startCrawling($detailUrl);
    }
}
