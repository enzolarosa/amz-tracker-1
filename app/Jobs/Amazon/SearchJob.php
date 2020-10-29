<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\SearchCrawler;
use App\Crawler\CrawlRequestFulfilled;
use App\Models\SearchList;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class SearchJob extends Amazon
{
    protected SearchList $searchList;
    protected ?string $url;
    protected array $countries;

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'keyword:' . $this->searchList->keywords, 'url:' . $this->url];
    }

    /**
     * Create a new job instance.
     *
     * @param SearchList $searchList
     * @param array $countries
     * @param string|null $url
     */
    public function __construct(SearchList $searchList, array $countries = ['IT'], string $url = null)
    {
        parent::__construct('');

        $this->onQueue('amz-search');

        $this->searchList = $searchList;
        $this->countries = $countries;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $search = $this->url ?? sprintf("https://www.amazon.it/s?k=%s", urlencode($this->searchList->keywords));

        if ($this->shouldRelease($search)) {
            return;
        }

        // Get Product Details
        $observer = new SearchCrawler();
        $observer->setCountry(Arr::first($this->countries));
        $observer->setSearchList($this->searchList);

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
            ->startCrawling($search);
    }
}
