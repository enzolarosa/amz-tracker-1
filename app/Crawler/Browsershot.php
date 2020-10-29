<?php

namespace App\Crawler;

use Spatie\Browsershot\Browsershot as Browser;

class Browsershot extends Browser
{
    public function getCookie(): string
    {
        $command = $this->createCommand($this->url, 'cookie');

        return $this->callBrowser($command);
    }
}
