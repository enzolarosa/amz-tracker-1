<?php

namespace App\Console\Commands;

use App\CrawlerObservers\Amazon\GetProductDetail;
use App\Providers\AmzProvider;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;

class TestCrawlerObserversCommand extends Command
{
    protected $signature = 'crawler:amz';
    protected $description = 'Test Crawler Observers for amz';

    public function handle()
    {
        $url = "https://www.amazon.it/dp/B005CQ2ZY6";
//        $url = "https://www.engagepeople.com";
        Crawler::create()
            ->setBrowsershot($this->browsershot())
            ->executeJavaScript()
            ->setUserAgent('AmzTracker:' . AmzProvider::version())
//            ->ignoreRobots()
//            ->acceptNofollowLinks()
//            ->setConcurrency(1)
            ->setCrawlObserver(new GetProductDetail)
            ->startCrawling($url);
    }

    protected function browsershot(): Browsershot
    {
        return (new Browsershot())
            ->setNodeBinary(config('amz.node'))
            ->setNpmBinary(config('amz.npm'));
//            ->userAgent('AmzTracker:' . AmzProvider::version())
//            ->setExtraHttpHeaders([
//                'Accept-Encoding' => 'gzip, deflate, br',
//                'Connection' => 'keep-alive',
//                'X-Requested-With' => 'XMLHttpRequest',
//                'Accept' => 'text/html,*/*',
//                'Content-Type' => 'application/x-www-form-urlencoded',
//            ]);
    }
}
