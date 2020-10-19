<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

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

        if (!$user->batch_id) {
            $batch = Bus::batch([])->onQueue('telegram-batch')->name("Telegram User #$user->tId $user->first_name $user->last_name")->dispatch();
            $user->batch_id = $batch->id;
            $user->save();
        }

        $this->replyWithMessage(['text' => sprintf('Hello %s! Welcome to our bot, Here are our available commands:', $user->first_name)]);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $commands = $this->getTelegram()->getCommands();

        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
