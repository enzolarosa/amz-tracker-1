<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleWebhook()
    {
        $update = Telegram::commandsHandler(true);

        return 'ok';
    }
}
