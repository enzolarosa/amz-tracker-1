<?php

namespace App\Console\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\User;
use App\Notifications\ProductPriceChangedNotification;
use Illuminate\Console\Command;

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
        $asin = "B07N73J58V";
        $asin = "B01J7QLSB2";

        $job = new AmazonProductJob($asin);
        dispatch_now($job);
        $this->comment("\nDone!");

      /*  $user = User::findOrFail(1);
        $prod = AmzProduct::query()->where('asin', $asin)->first();

        $not = new ProductPriceChangedNotification();
        $not->setProduct($prod);
        $user->notify($not);*/
    }
}
