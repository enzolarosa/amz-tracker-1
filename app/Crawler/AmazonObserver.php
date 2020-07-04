<?php

namespace App\Crawler;

use App\Models\PriceTrace;
use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class AmazonObserver extends CrawlObserver
{
    protected PriceTrace $product;

    /**
     * @return PriceTrace
     */
    public function getProduct(): PriceTrace
    {
        return $this->product;
    }

    /**
     * @param PriceTrace $product
     */
    public function setProduct(PriceTrace $product): void
    {
        $this->product = $product;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawled() method.
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
    }
}
