<?php

namespace App\Crawler;

use App\Common\Constants;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Handlers\CrawlRequestFulfilled as CrawlRequest;

class CrawlRequestFulfilled extends CrawlRequest
{
    protected function getBodyAfterExecutingJavaScript(UriInterface $url): string
    {
        $browsershot = $this->crawler->getBrowsershot();
        $html = $browsershot->setUrl((string)$url)->bodyHtml();

        $cookies = optional(json_decode($browsershot->getCookie()))->{'cookies'};
        Cache::put(Constants::getAmzCookiesKey(), json_encode($cookies), $this->getTtl($cookies));

        return html_entity_decode($html);
    }

    protected function getTtl($cookies)
    {
        $ttl = null;
        foreach ($cookies as $cookie) {
            if ($cookie->name == 'session-id-time') {
                $ttl = $cookie->expires;
            }
        }

        return $ttl;
    }
}
