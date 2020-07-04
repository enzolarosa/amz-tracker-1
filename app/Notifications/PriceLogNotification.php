<?php

namespace App\Notifications;

use App\Models\PriceTrace;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class PriceLogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected PriceTrace $product;

    public function __construct()
    {
        $this->onQueue('notification-queue');
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * @param $notifiable
     * @return TelegramMessage
     * @throws Exception
     */
    public function toTelegram($notifiable)
    {
        $url = $this->getAmzUrl();
        info($notifiable->toArray());
        return TelegramMessage::create()
            ->to($notifiable->tId)
            ->content("Il *prezzo* del prodotto *{$this->getProduct()->name}* su amazon è *diminuito*!")
            ->button('Commpralo ora!', $url);

        // OR using a helper method with or without a remote file.
        // ->photo('https://file-examples.com/wp-content/uploads/2017/10/file_example_JPG_1MB.jpg');
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getAmzUrl(): string
    {
        switch ($this->getProduct()->store) {
            case 'IT':
                return sprintf("https://www.amazon.it/gp/%s?tag=amazon07cff-21", $this->getProduct()->product_id);
            default:
                throw new Exception('Not supported');
        }
    }

    /**
     * @return PriceTrace
     */
    public function getProduct(): PriceTrace
    {
        return $this->product;
    }

    /**
     * @param PriceTrace $product
     */
    public function setProduct(PriceTrace $product): void
    {
        $this->product = $product;
    }
}
