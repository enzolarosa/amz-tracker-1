<?php

namespace App\Jobs\Amazon;

use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Jobs\Job;
use App\Logging\GuzzleLogger;
use DateTime;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Spatie\RateLimitedMiddleware\RateLimited;

class Amazon extends Job
{
    const WAIT_CRAWLER = 30;

    protected int $concurrency = 1;
    protected int $delayBtwRequest = 25;

    protected ?string $asin = null;
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
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'asin:' . $this->asin];
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(10)
            ->everyMinutes(2)
            ->releaseAfterMinutes(10)
            ->releaseAfterBackoff($this->attempts());

        return [$rateLimitedMiddleware];
    }

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

    protected function clientOptions(?CookieJar &$cookieJar = null): array
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

        $opt = [
            'handler' => $handler,
            RequestOptions::VERIFY => config('app.env') !== 'local',
            RequestOptions::CONNECT_TIMEOUT => 60 * 8,
            RequestOptions::TIMEOUT => 60 * 8,
            RequestOptions::HEADERS => [
                'User-Agent' => Arr::random(UserAgent::get()),
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept' => 'text/html,*/*',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];
        if (!is_null($cookieJar)) {
            $opt[RequestOptions::COOKIES] = $cookieJar;
        }

        return $opt;
    }

    /**
     * @return Browsershot
     */
    protected function browsershot(): Browsershot
    {
        $browsershot = new Browsershot();
        $browsershot->setNodeBinary(env('NODE_PATH'));
        $browsershot->setNpmBinary(env('NPM_PATH'));
        $browsershot->setBinPath(app_path('Crawler/bin/browser.js'));
        $browsershot->userAgent(Arr::random(UserAgent::get()));
        $browsershot->setExtraHttpHeaders([
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'text/html,*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);
        return $browsershot;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addDay();
    }
}
