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
        Cache::put(Constants::COOKIES_KEY, $cookies);
        info("write cookie to cache. " . $cookies);
        return html_entity_decode($html);
    }
}
