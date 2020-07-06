<?php

namespace App\Telegram\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class AddProductCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "add";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{asin?}';

    /**
     * @var string Command Description
     */
    protected $description = "Add product to tracker list";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $tUser = $this->getUpdate()->getMessage()->getFrom();
        $args = $this->getArguments();
        Log::debug($args);

        $user = User::query()->updateOrCreate([
            'tId' => $tUser->id
        ], [
            'first_name' => $tUser->first_name,
            'last_name' => $tUser->last_name,
            'username' => $tUser->username,
            'language_code' => $tUser->language_code,
            'active' => true,
        ]);

        $asin = 'B01J7QLSB2';// $args['asin'];
        $product = AmzProduct::query()->firstOrCreate(['asin' => $asin]);
        $product->users()->save($user);
        $job = new AmazonProductJob($asin);
        dispatch($job);

        $this->replyWithMessage(['text' => sprintf('Your product: %s added to tracker list.', $asin)]);
    }
}
