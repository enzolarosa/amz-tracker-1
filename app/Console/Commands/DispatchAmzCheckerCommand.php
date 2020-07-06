<?php

namespace App\Console\Commands;

use App\Jobs\AmazonProductJob;
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
        $job = new AmazonProductJob($asin);
        dispatch_now($job);
        // $this->comment("I've {$pt->count()} product to check!");

        // $bar = $this->output->createProgressBar($pt->count());
        //  $bar->start();

        //   $pt->each(function (PriceTrace $product) use ($bar) {
        //      $bar->advance();
        //  });

        // $bar->finish();
        $this->comment("\nDone!");
    }
}
