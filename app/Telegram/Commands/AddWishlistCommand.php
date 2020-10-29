<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Models\WishList;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class AddWishlistCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "wishlist";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{url}';

    /**
     * @var string Command Description
     */
    protected $description = "Add product from your wishlist to tracker list";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $tUser = $this->getUpdate()->getMessage()->getFrom();
        $args = $this->arguments;

        $user = User::query()->updateOrCreate([
            'tId' => $tUser->id
        ], [
            'first_name' => $tUser->first_name,
            'last_name' => $tUser->last_name,
            'username' => $tUser->username,
            'language_code' => $tUser->language_code,
            'active' => true,
        ]);

        $str = $args['url'] ?? null;
        if (is_null($str) || empty($str)) {
            $this->replyWithMessage(['text' => 'Please give me a valid `url`']);
            return;
        }

        WishList::query()->firstOrCreate([
            'trackable_id' => $user->id,
            'trackable_type' => User::class,
            'url' => $str,
        ]);

        $this->replyWithMessage(['text' => sprintf('I will add all product in your wishlist in your tracker list.')]);
    }
}
