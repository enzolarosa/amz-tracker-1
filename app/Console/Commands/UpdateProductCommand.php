<?php

namespace App\Console\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\Setting;
use Illuminate\Console\Command;
use Bus;
use Illuminate\Support\Facades\DB;
use Throwable;

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
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $minutes = (int)Setting::read('check_product_minutes')->value ?? 30;

        $prod = AmzProduct::query()
            ->select('amz_products.*')
            //->leftJoin('amz_product_queues', 'amz_product_queues.amz_product_id', '=', 'amz_products.id')
            // ->whereNull('amz_product_queues.id')
            ->where('amz_products.enabled', true)
            ->where('amz_products.updated_at', '<=', now()->subMinutes($minutes));

        $this->comment("I've {$prod->count()} products to analyze!");

        $bar = $this->output->createProgressBar($count = $prod->count());
        $bar->start();

        if ($count > 0) {
            $batch = Bus::batch([])
                ->onQueue('check-amz-product')
                ->name("[" . now()->format('d M h:i') . "] UpdateProductCommand running")
                ->dispatch();

            $prod->each(function (AmzProduct $product) use ($bar, $batch) {
                $job = new AmazonProductJob($product->asin, $batch->id);
                $product->touch();

                $batch->add([$job]);
                $bar->advance();
            });
        }

        $bar->finish();
        $this->comment("\nDone!");
    }
}
