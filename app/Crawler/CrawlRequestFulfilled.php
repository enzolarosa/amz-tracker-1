<?php

namespace App\Crawler;

use App\Models\Setting;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Handlers\CrawlRequestFulfilled as CrawlRequest;

class CrawlRequestFulfilled extends CrawlRequest
{
    const COOKIES_KEY = "amz_cookies";

    protected function getBodyAfterExecutingJavaScript(UriInterface $url): string
    {
        $browsershot = $this->crawler->getBrowsershot();

        /*$cookies = $this->getCookies();
        if (!is_null($cookies)) {
            $browsershot->setOption('cookies', json_decode($cookies));
        }*/

        $html = $browsershot->setUrl((string)$url)->bodyHtml();

        /*$cookies = optional(json_decode($browsershot->getCookie()))->{'cookies'};
        Setting::store(self::COOKIES_KEY, $cookies, now()->addHours(2));*/

        $cookies = optional(json_decode($browsershot->getCookie()))->{'cookies'};
        info("cookies: " . json_encode($cookies));
        return html_entity_decode($html);
    }

    protected function getCookies()
    {
        return Setting::read(self::COOKIES_KEY)->value;
    }
}
