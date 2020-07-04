<?php

namespace App\Crawler;

use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class AmazonItObserver extends AmazonObserver
{
    /**
     * @inheritDoc
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());

        $salePrice = $doc->getElementById('priceblock_saleprice')->nodeValue;

        $currentPrice = (float)str_replace(['€', ' '], '', $salePrice);
        $firstPrice = $this->getProduct()->first_price ?? $currentPrice;
        $latestPrice = $this->getProduct()->current_price;

        $this->getProduct()->update([
            'first_price' => $firstPrice,
            'latest_price' => $latestPrice,
            'current_price' => $currentPrice,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        dd("crawlFail", $url, $requestException, $foundOnUrl);
    }
}
