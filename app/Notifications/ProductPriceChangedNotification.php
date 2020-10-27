<?php

namespace App\Notifications;

use App\Models\AmzProduct;
use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;
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
        $img = Arr::first($this->getProduct()->images) ?? null;
        $msg = sprintf(
            "📦 %s

⭐️ %s
‼️ Prezzo ribassato
💰 *%s* invece di %s

🌐 %s

🗣 [Invita i tuoi amici](%s)
🤖 [@AmzTrackerBot](%s)",
            substr($this->getProduct()->title, 0, 150) . '...',
            $this->getProduct()->stars,
            number_format($this->price, 2, ',', '.') . "€",
            number_format($this->previous_price, 2, ',', '.') . "€",
            ShortUrl::hideLink($this->getProduct()->itemDetailUrl . '?tag=' . env('AMZ_PARTNER')),
            'https://t.me/share/url?url=https://t.me/' . env('TELEGRAM_CHANNEL', 'minimoprezzo'),
            'https://t.me/share/url?url=https://t.me/amztracker_bot&text=' . rawurlencode('Tieni sott\'occhio i prodotti su amazon e ricevi anche tu una notifica quando il prezzo scende!')
        );

        $tMsg = TelegramMessage::create();
        if ($img) {
            $tMsg = TelegramFile::create()
                ->photo($img);
        }

        if ($this->disableNotification($notifiable)) {
            $tMsg->disableNotification(true);
        }

        return $tMsg->content($msg);
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

    protected function disableNotification($notifiable): bool
    {
        $now = Carbon::now();

        $start = Carbon::createFromTimeString('19:00', 'Europe/Rome');
        $end = Carbon::createFromTimeString('09:00', 'Europe/Rome')->addDay();

        return $now->between($start, $end);
    }
}
