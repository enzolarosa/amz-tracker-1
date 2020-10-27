<?php

namespace App\Telegram\Commands;

use App\Models\SearchList;
use App\Models\User;
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

        $user = User::query()->updateOrCreate([
            'tId' => $tUser->id
        ], [
            'first_name' => $tUser->first_name,
            'last_name' => $tUser->last_name,
            'username' => $tUser->username,
            'language_code' => $tUser->language_code,
            'active' => true,
        ]);

        $str = trim(str_replace('/search', '', $this->getUpdate()->getMessage()->text)) ?? null;
        if (is_null($str) || empty($str)) {
            $this->replyWithMessage(['text' => 'Please give me a valid `keyword`']);
            return;
        }

        SearchList::query()->create([
            'trackable_id' => $user->id,
            'trackable_type' => User::class,
            'keywords' => $str
        ]);

        $this->replyWithMessage(['text' => sprintf('I will add each results for `%s` in your tracker list.', $str)]);
    }
}
