<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\WishlistCrawler;
use App\Crawler\CrawlRequestFulfilled;
use App\Models\WishList;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class WishlistJob extends Amazon
{
    protected $tracker;
    protected WishList $list;
    protected array $countries;

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'product-list', 'url:' . $this->list->url];
    }

    /**
     * Create a new job instance.
     *
     * @param WishList $list
     * @param array $countries
     */
    public function __construct(WishList $list, array $countries = ['IT'])
    {
        parent::__construct('');

        $this->onQueue('amz-search');

        $this->countries = $countries;
        $this->list = $list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->shouldRelease($this->list->url)) {
            return;
        }

        // Get Product Details
        $observer = new WishlistCrawler();
        $observer->setCountry(Arr::first($this->countries));
        $observer->setTracker($this->list->trackable());

        if (!is_null($this->batch())) {
            $observer->setBatchId($this->batch()->id);
        }

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
            ->startCrawling($this->list);
    }
}
