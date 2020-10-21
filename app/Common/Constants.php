<?php

namespace App\Common;

class Constants
{
    public static int $WAIT_CRAWLER = 10; // seconds
    public static int $SLEEP_CRAWLER = 10; // seconds
    public static int $TRACKER_TIMEOUT = 30; // minutes
    public static int $WAIT_AMZ_HTTP_ERROR = 5;// minutes
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
