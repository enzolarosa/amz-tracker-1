<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Crawler\ComuniCitta;
use App\Events\ProductPriceChangedEvent;
use App\Jobs\Amazon\ProductDetailsJob;
use App\Jobs\Amazon\WishlistJob;
use App\Jobs\AmazonProductJob;
use App\Logging\GuzzleLogger;
use App\Models\AmzProduct;
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
        $url = 'https://www.amazon.it/hz/wishlist/ls/DRBE4G2NQBCC/ref=nav_wishlist_lists_1?_encoding=UTF8&type=wishlist';

        $asin = 'B0858YWBGT';
        $product =  AmzProduct::query()->firstOrCreate(['asin' => $asin]);
        $job = new ProductDetailsJob($asin);

        dispatch_now($job);

        $event = new ProductPriceChangedEvent();
        $event->setProduct($product);
        event($event);
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
