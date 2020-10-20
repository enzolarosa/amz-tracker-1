<?php

namespace App\Console\Commands;

use App\Jobs\Amazon\WishlistJob;
use App\Models\WishList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

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
     */
    public function handle()
    {
        $result = DB::select("select * from job_batches where name like 'UpdateWishListCommand%' order by created_at desc limit 1;");
        $batchId = null;

        if (!empty($result)) {
            $batchId = $result[0]->id;
        }

        if ($batchId) {
            $batch = Bus::findBatch($batchId);
        } else {
            $batch = Bus::batch([])->onQueue('check-amz-product')->name("UpdateWishListCommand running")->dispatch();
        }

        WishList::query()->each(function (WishList $list) use ($batch) {
            $batch->add([new WishlistJob($list->url)]);
        });
    }
}
