<?php

namespace App\Telegram\Commands;

use App\Models\AmzProduct;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
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

        if ($user->batch_id) {
            $batch = Bus::findBatch($user->batch_id);
            DB::statement("update job_batches set finished_at = null where id = '$user->batch_id';");
        } else {
            $batch = Bus::batch([])->onQueue('telegram-batch')->name("Telegram User #$user->tId $user->first_name $user->last_name")->dispatch();
            $user->batch_id = $batch->id;
            $user->save();
        }

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
