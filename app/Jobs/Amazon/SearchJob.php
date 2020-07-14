<?php

namespace App\Jobs\Amazon;

use App\Crawler\Amazon\SearchCrawler;
use App\Models\User;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Arr;
use Spatie\Crawler\Crawler;

class SearchJob extends Amazon
{
    protected ?User $user = null;
    protected ?string $keyword;
    protected ?string $url;
    protected array $countries;

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'keyword:' . $this->keyword];
    }

    /**
     * Create a new job instance.
     *
     * @param string $keyword
     * @param array $countries
     * @param string|null $url
     */
    public function __construct(string $keyword, array $countries = ['IT'], string $url = null)
    {
        $this->onQueue('amz-search');

        $this->keyword = $keyword;
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
        $search = $this->url ?? sprintf("https://www.amazon.it/s?k=%s", urlencode($this->keyword));

        // Get Product Details
        $observer = new SearchCrawler();
        $observer->setCountry(Arr::first($this->countries));
        $observer->setUser($this->getUser());

        $jar = session('amz_cookies', new CookieJar);
        Crawler::create($this->clientOptions($jar))
            ->ignoreRobots()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->startCrawling($search);

        session(['amz_cookies' => $jar]);
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