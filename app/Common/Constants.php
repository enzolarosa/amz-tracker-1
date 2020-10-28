<?php

namespace App\Common;

class Constants
{
    public static int $TRACKER_TIMEOUT = 40; // minutes
    public static int $CONNECTION_TIMEOUT = 3;// minutes

    public static function getAmzCookiesKey(): string
    {
        return sprintf('%s-amz_cookies', php_uname('u'));
    }

    public static function getAmzHttpLimitKey(): string
    {
        return sprintf('%s-amz-http-limit', php_uname('u'));
    }
}
