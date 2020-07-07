<?php

namespace App\Notifications;

use App\Models\AmzProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class ProductPriceChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected AmzProduct $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('notification');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content(sprintf("Hi I've good new\nYour product '*%s*' price now is *%s* (preview: %s)", $this->getProduct()->title, $this->getProduct()->current_price, $this->getProduct()->preview_price))
            ->button('Buy product', $this->getProduct()->itemDetailUrl . '?tag=' . env('AMZ_PARTNER'));
    }

    /**
     * @return AmzProduct
     */
    public function getProduct(): AmzProduct
    {
        return $this->product;
    }

    /**
     * @param AmzProduct $product
     */
    public function setProduct(AmzProduct $product): void
    {
        $this->product = $product;
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
           TelegramChannel::class => 'notify-telegram',
            'slack' => 'slack-queue',
        ];
    }
}
