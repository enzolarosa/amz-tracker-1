<?php

namespace App\Telegram\Commands;

use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
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
    protected $pattern = '{asin}';

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

        $str = $args['asin'] ?? null;
        if (is_null($str) || empty($str)) {
            $this->replyWithMessage(['text' => 'Please give me a valid `asin` string']);
            return;
        }

        preg_match('/([A-Z0-9]{10})/', $str, $prod, PREG_OFFSET_CAPTURE);
        $asin = optional(optional($prod)[0])[0];

        if (is_null($asin) || empty($asin)) {
            $this->replyWithMessage(['text' => 'Please give me a valid `asin` string']);
            return;
        }

        $product = AmzProduct::query()->firstOrCreate(['asin' => $asin]);

        AmzProductUser::query()->updateOrCreate([
            'user_id' => $user->id,
            'amz_product_id' => $product->id
        ], [
            'enabled' => true
        ]);

        if ($user->batch_id) {
            $batch = Bus::findBatch($user->batch_id);
        } else {
            $batch = Bus::batch([])->onQueue('telegram-batch')->name("Telegram User #$user->tId $user->first_name $user->last_name")->dispatch();
            $user->batch_id = $batch->id;
            $user->save();
        }

        $job = new AmazonProductJob($asin, $user->batch_id);
        $batch->add([$job]);

        $this->replyWithMessage(['text' => sprintf('Your product: %s added to tracker list.', $asin)]);
    }
}
