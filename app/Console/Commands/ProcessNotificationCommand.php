<?php

namespace App\Console\Commands;

use App\Models\AmzProduct;
use App\Models\Channels;
use App\Models\Notification;
use App\Notifications\ChannelsNotification;
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

        $bar = $this->output->createProgressBar($count = $nots->count());
        $bar->start();

        if ($count > 0) {
            $nots->each(function (Notification $not) use ($bar) {
                $notification = new ProductPriceChangedNotification();

                $prod = AmzProduct::find($not->amz_product_id);
                $route = $not->notificable;

                if ($route instanceof Channels) {
                    $notification = new ChannelsNotification();
                }

                $notification->setProduct($prod);
                $notification->setPreviousPrice($not->previous_price);
                $notification->setPrice($not->price);

                $route->notify($notification);

                $not->sent = true;
                $not->save();

                $bar->advance();
            });
        }

        $bar->finish();
        $this->comment("\nDone!");
    }
}
