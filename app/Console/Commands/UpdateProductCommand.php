<?php

namespace App\Console\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use Illuminate\Console\Command;

class UpdateProductCommand extends Command
{
    const WAIT_CRAWLER = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:update-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all enabled product and update their price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $prod = AmzProduct::query()
            ->select('amz_products.*')
            ->leftJoin('amz_product_queues', 'amz_product_queues.amz_product_id', '=', 'amz_products.id')
            ->whereNull('amz_product_queues.id')
            ->where('amz_products.enabled', true)
            ->where('amz_products.updated_at', '<=', now()->subMinutes(45));

        $this->comment("I've {$prod->count()} products to analyze!");

        $bar = $this->output->createProgressBar($prod->count());
        $bar->start();
        $waitSec = 0;

        $prod->each(function (AmzProduct $product) use ($bar, &$waitSec) {
            $job = new AmazonProductJob($product->asin);
            dispatch($job);//->delay(now()->addSeconds($waitSec));

            $waitSec += self::WAIT_CRAWLER;
            $bar->advance();
        });
        $bar->finish();
        $this->comment("\nDone!");
    }
}
