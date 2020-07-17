<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\DetailsCrawler;
use GuzzleHttp\Cookie\CookieJar;
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

        $jar = session('amz_cookies', new CookieJar);
        Crawler::create($this->clientOptions($jar))
            ->ignoreRobots()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->setBrowsershot($browsershot)->executeJavaScript()
            ->startCrawling($detailUrl);
        session(['amz_cookies' => $jar]);
    }
}
