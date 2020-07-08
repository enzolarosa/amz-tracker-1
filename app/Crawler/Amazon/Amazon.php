<?php

namespace App\Crawler\Amazon;

use App\Models\AmzProduct;
use DOMDocument;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class Amazon extends CrawlObserver
{
    protected DOMDocument $doc;
    protected ResponseInterface $response;
    protected string $currency;
    protected string $asin;
    protected string $country;
    protected string $shopUrl;

    /**
     * @return string
     */
    public function getShopUrl(): string
    {
        return $this->shopUrl;
    }

    /**
     * @param string $shopUrl
     */
    public function setShopUrl(string $shopUrl): void
    {
        $this->shopUrl = $shopUrl;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getAsin(): string
    {
        return $this->asin;
    }

    /**
     * @param string $asin
     */
    public function setAsin(string $asin): void
    {
        $this->asin = $asin;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $this->doc = $doc;
        $this->response = $response;

        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->getAsin()]);

        $data = $this->parsePage();
        if (!is_null($data)) {
            if (!is_null($prod->current_price)) {
                $prod->update(['preview_price' => $prod->current_price]);
            }
            $prod->update($data);
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
        $status = $requestException->getResponse()->getStatusCode();
        if ($status == Response::HTTP_NOT_FOUND) {
            $prod = AmzProduct::query()->where('asin', $this->getAsin())->update(['enabled' => false]);
        }
        $msg = sprintf(
            'The `%s` link have some issues: status code `%s` message: %s',
            $requestException->getRequest()->getUri(), $status, $requestException->getMessage(),
        );
        throw new Exception($msg);
    }

    protected function parsePage()
    {
        // TODO: Implement parsePage() method.
    }
}
