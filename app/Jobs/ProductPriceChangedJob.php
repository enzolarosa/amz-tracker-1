<?php

namespace App\Jobs;

use App\Models\AmzProduct;
use App\Models\Notification;
use App\Models\User;
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
        $this->product->users()->where('active', true)->each(function (User $user) {
            if ($this->shouldNotify($user)) {
                Notification::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'amz_product_id' => $this->product->id,
                    'price' => $this->product->current_price,
                    'previous_price' => $this->previous_price,
                    'sent' => false,
                ]);
            }
        });
    }

    protected function shouldNotify(User $user): bool
    {
        // TODO add user custom logic
        return true;
    }
}
