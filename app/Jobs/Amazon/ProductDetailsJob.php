<?php

namespace App\Jobs\Amazon;

use App\Common\Constants;
use App\Crawler\Amazon\DetailsCrawler;
use App\Crawler\CrawlRequestFulfilled;
use App\Models\AmzProduct;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class ProductDetailsJob extends Amazon
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = AmzProduct::query()->where('asin', $this->asin)->first();
        if (!(is_null($product->title) || $product->created_at <= now()->subWeek())) {
            return;
        }

        $detailUrl = $this->getProductUrl('detail');
        $shopUrl = $this->getProductUrl('shop');

        if ($this->shouldRelease($detailUrl)) {
            return;
        }

        // Get Product Details
        $observer = new DetailsCrawler();
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
            ->startCrawling($detailUrl);
    }
}
