<?php

namespace App\Notifications;

use App\Models\AmzProduct;
use App\Models\ShortUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class ProductPriceChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected AmzProduct $product;
    protected $previous_price;
    protected $price;

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

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            TelegramChannel::class => 'notify-telegram',
        ];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content(sprintf(
                "I've good news for you!\nYour favorites product '*%s*' has now a new price *%s*. (previous: %s)\nLink: %s",
                substr($this->getProduct()->title, 0, 20) . '...',
                number_format($this->price, 2, ',', '.') . "€",
                number_format($this->previous_price, 2, ',', '.') . "€",
                ShortUrl::hideLink($this->getProduct()->itemDetailUrl . '?tag=' . env('AMZ_PARTNER'))
            ));
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
     * @return mixed
     */
    public function getPreviousPrice()
    {
        return $this->previous_price;
    }

    /**
     * @param mixed $previous_price
     */
    public function setPreviousPrice($previous_price): void
    {
        $this->previous_price = $previous_price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }
}
