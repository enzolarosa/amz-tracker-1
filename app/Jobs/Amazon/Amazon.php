<?php

namespace App\Jobs\Amazon;

use App\Common\Constants;
use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Jobs\Job;
use App\Logging\GuzzleLogger;
use App\Models\ProxyServer;
use DateTime;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;

class Amazon extends Job
{
    public $tries = 15;

    const WAIT_CRAWLER = 60;

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

    protected function clientOptions(?CookieJar $cookieJar = null, bool $proxy = false): array
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
            RequestOptions::CONNECT_TIMEOUT => 60 * Constants::$CONNECTION_TIMEOUT,
            RequestOptions::TIMEOUT => 60 * Constants::$CONNECTION_TIMEOUT,
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
            $cookies = Cache::get(Constants::COOKIES_KEY);
            info("cookies cache:" . json_encode($cookies));
            $cookieJar = new CookieJar(true, $cookies);
        }
        $opt[RequestOptions::COOKIES] = $cookieJar;

        if ($proxy) {
            $opt[RequestOptions::PROXY] = ProxyServer::giveOne();
        }

        return $opt;
    }

    /**
     * @return Browsershot
     */
    protected function browsershot(): Browsershot
    {
        return (new Browsershot())
            ->setNodeBinary(env('NODE_PATH'))
            ->setNpmBinary(env('NPM_PATH'))
            ->setBinPath(app_path('Crawler/bin/browser.js'))
            ->userAgent(Arr::random(UserAgent::get()))
            ->noSandbox()
            ->setExtraHttpHeaders([
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept' => 'text/html,*/*',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->addChromiumArguments([
                'window-size' => '1920,1080',
            ]);
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addDay();
    }

    protected function shouldRelease(string $url): bool
    {
        if ($this->batch()->cancelled()) {
            return true;
        }

        if ($timestamp = Cache::get('amz-http-limit')) {
            $this->release($timestamp - time());
            return true;
        }

        $response = Http::withHeaders([
            'User-Agent' => Arr::random(UserAgent::get()),
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'text/html,*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->timeout(30)->get($url);

        if ($response->failed()) {
            info("endpoint $url failed statusCode: {$response->status()}");
        }

        if ($response->failed() && in_array($response->status(), [429, 503])) {
            info("body job: " . json_encode($response->body()));

            // $secondsRemaining = $response->header('Retry-After');
            $secondsRemaining = self::WAIT_CRAWLER;

            Cache::put('amz-http-limit', now()->addSeconds($secondsRemaining)->timestamp, $secondsRemaining);

            $this->release((int)$secondsRemaining);
            return true;
        }

        return false;
    }
}
