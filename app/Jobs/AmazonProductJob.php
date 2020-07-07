<?php

namespace App\Jobs;

use App\Crawler\Amazon\DetailsCrawler;
use App\Crawler\Amazon\OffersCrawler;
use App\Logging\GuzzleLogger;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Spatie\Crawler\Crawler;

class AmazonProductJob extends Job
{
    protected string $asin;
    protected array $countries;

    protected array $baseUrls = [
        'US' => 'https://www.amazon.com',
        'UK' => 'https://www.amazon.co.uk',
        'DE' => 'https://www.amazon.de',
        'ES' => 'https://www.amazon.es',
        'FR' => 'https://www.amazon.fr',
        'IT' => 'https://www.amazon.it',
        'IN' => 'https://www.amazon.in',
        'CA' => 'https://www.amazon.ca',
        'JP' => 'https://www.amazon.co.jp',
    ];
    protected array $currency = [
        'US' => 'USD',
        'UK' => 'GBP',
        'DE' => 'EUR',
        'ES' => 'EUR',
        'FR' => 'EUR',
        'IT' => 'EUR',
        'IN' => 'INR',
        'CA' => 'CAD',
        'JP' => 'JPY',
    ];

    /**
     * Create a new job instance.
     *
     * @param string $asin
     * @param array $countries
     */
    public function __construct(string $asin, array $countries = ['IT'])
    {
        $this->onQueue('amz-product');

        $this->asin = $asin;
        $this->countries = $countries;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $detailUrl = $this->getProductUrl('detail');
        $offerUrl = $this->getProductUrl('offer');
        $shopUrl = $this->getProductUrl('shop');

        // Get Product Details
        $observer = new DetailsCrawler();
        $observer->setCurrency($this->currency[Arr::first($this->countries)]);
        $observer->setAsin($this->asin);
        $observer->setCountry(Arr::first($this->countries));
        $observer->setShopUrl($shopUrl);

        Crawler::create($this->clientOptions())
            ->setCrawlObserver($observer)
            ->ignoreRobots()
            ->setMaximumCrawlCount(1)
            ->startCrawling($detailUrl);

        // Get Product Price
        $observer = new OffersCrawler();
        $observer->setCurrency($this->currency[Arr::first($this->countries)]);
        $observer->setAsin($this->asin);
        $observer->setCountry(Arr::first($this->countries));
        $observer->setShopUrl($shopUrl);

        Crawler::create($this->clientOptions())
            ->setCrawlObserver($observer)
            ->ignoreRobots()
            ->setMaximumCrawlCount(1)
            ->startCrawling($offerUrl);
    }

    protected function getProductUrl(string $type = ''): string
    {
        switch ($type) {
            case 'shop':
            case 'offer':
                $url = 'gp/offer-listing';
                break;
            case 'detail':
            default:
                $url = 'dp';
                break;
        }
        return "{$this->baseUrls[Arr::first($this->countries)]}/$url/{$this->asin}";
    }

    protected function clientOptions(): array
    {
        $handler = HandlerStack::create();
        $handler->push(Middleware::log(
            new Logger('ExtGuzzleLogger'),
            (new GuzzleLogger('{req_body} - {res_body}'))->setProvider('amz-crawler')
        ));

        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            $requestId = Arr::first($request->getHeader('X-Request-ID')) ?? (string)Str::uuid();
            return $request->withAddedHeader('X-Request-ID', $requestId);
        }));

        return [
            'verify' => config('app.env') !== 'local',
            //'handler' => $handler,
            'timeout' => 60,
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate, br',
                'User-Agent' => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15'
            ]
        ];
    }
}
