<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\OffersCrawler;
use App\Crawler\CrawlRequestFulfilled;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\Crawler\Crawler;

class ProductOffersJob extends Amazon
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $offerUrl = $this->getProductUrl('offer');
        $shopUrl = $this->getProductUrl('shop');

        if ($this->shouldRelease($offerUrl)) {
            return;
        }

        // Get Product Price
        $observer = new OffersCrawler();
        $observer->setCurrency($this->currency[Arr::first($this->countries)]);
        $observer->setAsin($this->asin);
        $observer->setCountry(Arr::first($this->countries));
        $observer->setShopUrl($shopUrl);

        Crawler::create($this->clientOptions())
            ->setCrawlFulfilledHandlerClass(CrawlRequestFulfilled::class)
            ->ignoreRobots()
            ->acceptNofollowLinks()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->setBrowsershot($this->browsershot())
            ->executeJavaScript()
            ->startCrawling($offerUrl);
    }
}
