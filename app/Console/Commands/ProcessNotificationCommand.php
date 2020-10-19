<?php

namespace App\Console\Commands;

use App\Models\AmzProduct;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\ProductPriceChangedNotification;
use Illuminate\Console\Command;

class ProcessNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amz:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all notification queue';

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
        $nots = Notification::query()->where('sent', false);
        $this->comment("I've {$nots->count()} notifications to send!");

        $bar = $this->output->createProgressBar($nots->count());
        $bar->start();

        $nots->each(function (Notification $not) use ($bar) {
            $prod = AmzProduct::find($not->amz_product_id);
            $user = User::find($not->user_id);

            $notification = new ProductPriceChangedNotification();
            $notification->setProduct($prod);
            $user->notify($notification);

            $not->delete();

            $bar->advance();
        });

        $bar->finish();
        $this->comment("\nDone!");
    }
}

