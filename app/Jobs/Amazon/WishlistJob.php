<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\WishlistCrawler;
use App\Crawler\Amazon\SearchCrawler;
use App\Crawler\CrawlRequestFulfilled;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class WishlistJob extends Amazon
{
    protected ?User $user = null;
    protected ?string $url;
    protected array $countries;

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'product-list'];
    }

    /**
     * Create a new job instance.
     *
     * @param array $countries
     * @param string|null $url
     */
    public function __construct(string $url = null, array $countries = ['IT'])
    {
        parent::__construct('');

        $this->onQueue('amz-search');

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
        if ($this->shouldRelease($this->url)) {
            return;
        }

        // Get Product Details
        $observer = new WishlistCrawler();
        $observer->setCountry(Arr::first($this->countries));
        $observer->setUser($this->getUser());

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
            ->startCrawling($this->url);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
