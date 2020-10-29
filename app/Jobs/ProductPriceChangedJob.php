<?php

namespace App\Jobs;

use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\Notification;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductPriceChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected AmzProduct $product;
    protected $previous_price;

    public function __construct(AmzProduct $product, $previous_price)
    {
        $this->product = $product;
        $this->previous_price = $previous_price;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->product->tracker()->where('enabled', true)->each(function (AmzProductUser $amzProductUser) {
            if ($this->shouldNotify($amzProductUser->trackable)) {
                Notification::query()->firstOrCreate([
                    'sent' => false,
                    'notificable_type' => get_class($amzProductUser->trackable),
                    'notificable_id' => $amzProductUser->trackable->id,
                    'amz_product_id' => $this->product->id,
                    'price' => $this->product->current_price,
                    'previous_price' => $this->previous_price,
                ]);
            }
        });
    }

    protected function shouldNotify($tracker): bool
    {
        // TODO add user custom logic
        return true;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addHour();
    }
}
