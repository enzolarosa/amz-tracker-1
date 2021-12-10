<?php

namespace App\CrawlerObservers\Amazon;

use DOMDocument;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class GetProductDetail extends CrawlObserver
{
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody()->getContents());

        $review = trim(optional($doc->getElementById('acrCustomerReviewText'))->nodeValue);
        $stars = trim(optional($doc->getElementById('acrPopover'))->getAttribute('title'));
        $featureDesc = preg_replace('/\s\s+/', '', trim(optional($doc->getElementById('featurebullets_feature_div'))->nodeValue));
        $desc = trim(optional($doc->getElementById('productDescription'))->nodeValue);
        $title = str_replace(PHP_EOL, '', optional($doc->getElementById('productTitle'))->nodeValue);
        $authors = optional($doc->getElementById('bylineInfo'))->nodeValue;

        dd(
            $response->getStatusCode()
//            ,$response->getHeaders()
//            ,$response->getBody()->getContents()
            , $title
            , $featureDesc
            , $desc
            , $review
            , $stars
            , $authors
        );
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        report($requestException);
        $response = $requestException->getResponse();
        dd(
            $url->getScheme()
            , $url->getHost()
            , $url->getPath()
            , $response->getStatusCode()
//            , $response->getHeaders()
//            , $response->getBody()->getContents()
        );
    }
}
