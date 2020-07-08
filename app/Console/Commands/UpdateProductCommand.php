<?php

namespace App\Console\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use Illuminate\Console\Command;

class UpdateProductCommand extends Command
{
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
        $prod = AmzProduct::query()->where('enabled', true)->where('updated_at', '<=', now()->subMinutes(30));
        $this->comment("I've {$prod->count()} products to analyze!");

        $bar = $this->output->createProgressBar($prod->count());
        $bar->start();

        $prod->each(function (AmzProduct $product) use ($bar) {
            $job = new AmazonProductJob($product->asin);
            dispatch($job);
            $bar->advance();
        });
        $bar->finish();
        $this->comment("\nDone!");
    }
}
