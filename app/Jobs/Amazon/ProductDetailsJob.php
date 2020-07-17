<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\DetailsCrawler;
use Illuminate\Support\Arr;
use Spatie\Browsershot\Browsershot;
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

        // Get Product Details
        $observer = new DetailsCrawler();
        $observer->setCurrency($this->currency[Arr::first($this->countries)]);
        $observer->setAsin($this->asin);
        $observer->setCountry(Arr::first($this->countries));
        $observer->setShopUrl($shopUrl);

        $browsershot = new Browsershot();
        $browsershot->setNodeBinary(env('NODE_PATH'));
        $browsershot->setNpmBinary(env('NPM_PATH'));
        $browsershot->setBinPath(app_path('Crawler/bin/browser.js'));

        Crawler::create($this->clientOptions())
            ->ignoreRobots()
            ->acceptNofollowLinks()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->setBrowsershot($browsershot)->executeJavaScript()
            ->startCrawling($detailUrl);
    }
}
