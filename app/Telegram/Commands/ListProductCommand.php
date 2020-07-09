<?php

namespace App\Telegram\Commands;

use App\Models\AmzProduct;
use App\Models\User;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ListProductCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "list";

    /**
     * @var string Command Description
     */
    protected $description = "Get your tracker list";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $tUser = $this->getUpdate()->getMessage()->getFrom();
        $user = User::query()->updateOrCreate([
            'tId' => $tUser->id
        ], [
            'first_name' => $tUser->first_name,
            'last_name' => $tUser->last_name,
            'username' => $tUser->username,
            'language_code' => $tUser->language_code,
            'active' => true,
        ]);

        $this->replyWithMessage(['text' => 'Following your tracker list']);
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $response = '';
        /** @var AmzProduct $product */
        foreach ($user->products as $product) {
            $response .= sprintf(
                '- %s - current price: %s %d' . PHP_EOL,
                $product->title,
                $product->currency,
                $product->current_price
            );
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
