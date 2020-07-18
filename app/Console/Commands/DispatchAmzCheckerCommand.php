<?php

namespace App\Console\Commands;

use App\Common\UserAgent;
use App\Crawler\Browsershot;
use App\Jobs\Amazon\SearchJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
        $search=[
            'Apple','Samsung','Xiaomi','DJI','Macbook pro','Synology','Ip Camera Synology','Synology','QNAP'
        ];

        $user = User::find(1);
        $job = new SearchJob(Arr::random($search));
        $job->setUser($user);
        dispatch_now($job);

        /*  $user = User::findOrFail(1);
          $prod = AmzProduct::query()->where('asin', $asin)->first();

          $not = new ProductPriceChangedNotification();
          $not->setProduct($prod);
          $user->notify($not);*/
    }


}
