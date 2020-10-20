<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StopCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "stop";

    /**
     * @var string Command Description
     */
    protected $description = "Disable your bot";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $tUser = $this->getUpdate()->getMessage()->getFrom();
        $user = User::query()->updateOrCreate([
            'tId' => $tUser->id
        ], [
            'first_name' => $tUser->first_name,
            'last_name' => $tUser->last_name,
            'username' => $tUser->username,
            'language_code' => $tUser->language_code,
            'active' => false,
            'batch_id' => null,
        ]);

        $this->replyWithMessage(['text' => sprintf("I'm sorry! Bye %s", $user->first_name)]);
    }
}
