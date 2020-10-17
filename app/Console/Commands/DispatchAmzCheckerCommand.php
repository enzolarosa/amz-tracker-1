<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Crawler\ComuniCitta;
use App\Logging\GuzzleLogger;
use App\Models\ProxyServer;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Spatie\Crawler\Crawler;

class DispatchAmzCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all amazon checker job';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*    $browsershot = new Browsershot();
            $browsershot->setNodeBinary(env('NODE_PATH'));
            $browsershot->setNpmBinary(env('NPM_PATH'));
            $browsershot->setBinPath(app_path('Crawler/bin/browser.js'));
            $browsershot->userAgent(Arr::random(UserAgent::get()));
            $browsershot->setExtraHttpHeaders([
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);

            $browsershot->setUrl((string)"https://www.amazon.it/s?k=smart+casa");
            $cookies = optional(json_decode($browsershot->getCookie()))->{'cookies'};
            $browsershot->setOption('cookies', $cookies);

            //dump( html_entity_decode($html));
            dd(json_encode($cookies), json_encode($cookiesPostHtml));
    */
        /*
        $search = [
            'Apple', 'Samsung', 'Xiaomi', 'DJI', 'Macbook pro', 'Synology', 'Ip Camera Synology', 'Synology', 'QNAP'
        ];
        $s = Arr::random($search);
      //  $user = User::find(1);
        $job = new SearchJob($s);
      //  $job->setUser($user);
        dispatch_now($job);*/
        /*$user = User::findOrFail(1);
                  $prod = AmzProduct::query()->where('asin', $asin)->first();

                  $not = new ProductPriceChangedNotification();
                  $not->setProduct($prod);
                  $user->notify($not);*/

        $via = strtolower(urlencode("Via amilcare Ponchielli Perugia"));
        $url = sprintf("https://www.mapdevelopers.com/what-is-my-zip-code.php?address=%s", $via);

        $observer = new ComuniCitta();
        // $observer->setAddress($address);
        dump($url);

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
            ->setDelay(15 * 1000);
    }

    protected int $concurrency = 1;
    protected int $delayBtwRequest = 25;

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
            $opt[RequestOptions::COOKIES] = $cookieJar;
        }

        if ($proxy) {
            $opt[RequestOptions::PROXY] = ProxyServer::giveOne();
        }

        return $opt;
    }
}
