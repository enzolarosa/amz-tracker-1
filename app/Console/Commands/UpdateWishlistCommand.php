<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\WishlistJob;
use App\Models\WishList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateWishlistCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:update-wishlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the product from the wishlist';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle()
    {
        $wishlists = WishList::query();
        $this->comment("I've {$wishlists->count()} wishlists to analyze!");

        $bar = $this->output->createProgressBar($count = $wishlists->count());
        $bar->start();

        $batch = Bus::batch([])
            ->onQueue('check-amz-product')
            ->name("[" . now()->format('md hi') . "] UpdateWishListCommand running")
            ->dispatch();
        
        if ($count > 0) {
            WishList::query()->each(function (WishList $list) use ($bar, $batch) {
                $list->touch();
                $batch->add([new WishlistJob($list->url)]);
                $bar->advance();
            });
        }

        $bar->finish();
        $this->comment("\nDone!");
    }
}
