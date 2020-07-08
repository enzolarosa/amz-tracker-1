<?php

namespace App\Common;

class UserAgent
{
    /**
     * @return array|string[]
     */
    public static function get(): array
    {
        return [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15',
        ];
    }
}
