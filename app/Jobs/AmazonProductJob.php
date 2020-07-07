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
            'timeout' => 60 * 8,
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'User-Agent' => Arr::random($this->userAgent()),
            ]
        ];
    }

    /**
     * @return array|string[]
     */
    protected function userAgent(): array
    {
        return [
            #Chrome
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (Windows NT 5.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            #Firefox
            'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
            'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 6.2; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)',
            'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)'
        ];
    }
}
