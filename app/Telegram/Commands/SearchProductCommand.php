<?php

namespace App\Telegram\Commands;

use App\Jobs\Amazon\SearchJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class SearchProductCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "search";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{keyword}';

    /**
     * @var string Command Description
     */
    protected $description = "Tracker all products for a given `keyword`";

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

        $str = $args['keyword'] ?? null;
        if (is_null($str) || empty($str)) {
            $this->replyWithMessage(['text' => 'Please give me a valid `keyword`']);
            return;
        }

        $searchJob = new SearchJob($str);
        $searchJob->setUser($user);

        Bus::batch([
            $searchJob
        ])->onQueue('amz-search')->name("[#{$user->tId} $user->username] Searching `$str` products")->dispatch();

        $this->replyWithMessage(['text' => sprintf('I will add each results for `%s` in your tracker list.', $str)]);
    }
}
