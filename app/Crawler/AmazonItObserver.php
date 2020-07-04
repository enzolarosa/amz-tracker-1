<?php

namespace App\Crawler;

use App\Models\PriceTraceLog;
use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Log;
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

        $salePrice = optional($doc->getElementById('priceblock_saleprice'))->nodeValue;
        $ourPrice = optional($doc->getElementById('priceblock_ourprice'))->nodeValue;

        if (!is_null($salePrice)) {
            $currentPrice = (float)str_replace(['€', ' '], '', $salePrice);
        } elseif (!is_null($ourPrice)) {
            $currentPrice = (float)str_replace(['€', ' '], '', $ourPrice);
        } else {
            $currentPrice = 0;
        }

        $firstPrice = $this->getProduct()->first_price ?? ($currentPrice === 0 ? null : $currentPrice);
        $latestPrice = $this->getProduct()->current_price == 0 ? $this->getProduct()->latest_price : $this->getProduct()->current_price;

        $this->getProduct()->update([
            'first_price' => $firstPrice,
            'latest_price' => $latestPrice,
            'current_price' => $currentPrice,
        ]);

        PriceTraceLog::query()->create([
            'price' => $currentPrice,
            'price_trace_id' => $this->getProduct()->id,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        Log::error($requestException->getMessage(), [
            'url' => $url,
            'foundOnUrl' => $foundOnUrl,
            'exception' => $requestException,
        ]);
        report($requestException);
    }
}
