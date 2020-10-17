<?php

namespace App\Jobs\Common;

use App\Common\Constants;
use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Crawler\ComuniCitta;
use App\Jobs\Job;
use App\Logging\GuzzleLogger;
use App\Models\Address;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Spatie\Crawler\Crawler;

class GetCap extends Job
{
    protected Address $address;

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this)];
    }

    public function handle()
    {
        $via = str_replace(
            [
                ',',
                '-',
                '.',
                ';',
            ],
            ' ',
            strtolower(urlencode(sprintf("%s %s", $this->address->indirizzo, $this->address->citta)))
        );
        $url = sprintf("https://www.mapdevelopers.com/what-is-my-zip-code.php?address=%s", $via);

        //dump(sprintf("Checking: %s", $url));
        echo "Fornitore: {$this->address->fornitore}" . PHP_EOL;
        $observer = new ComuniCitta();
        $observer->setAddress($this->address);

        Crawler::create($this->clientOptions())
            ->ignoreRobots()
            ->acceptNofollowLinks()
            ->setConcurrency($this->concurrency)
            ->setCrawlObserver($observer)
            ->setMaximumCrawlCount(1)
            ->setDelayBetweenRequests($this->delayBtwRequest)
            ->setBrowsershot($this->browsershot())
            ->executeJavaScript()
            ->startCrawling($url);
    }


    protected int $concurrency = 1;
    protected int $delayBtwRequest = 20;
    protected int $lazyJs = 15;

    protected function clientOptions(?CookieJar $cookieJar = null, bool $proxy = false): array
    {
        $handler = HandlerStack::create();
        $handler->push(Middleware::log(
            new Logger('ExtGuzzleLogger'),
            (new GuzzleLogger('{req_body} - {res_body}'))->setProvider('comuni-crawler')
        ));

        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            $requestId = Arr::first($request->getHeader('X-Request-ID')) ?? (string)Str::uuid();
            return $request->withAddedHeader('X-Request-ID', $requestId);
        }));

        return [
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
            ])
            ->setDelay($this->lazyJs * 1000);
    }
}
