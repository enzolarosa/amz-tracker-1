<?php

namespace App\Crawler\Amazon;

use App\Models\AmzProduct;
use DOMDocument;
use DOMElement;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
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

        $data = $this->parsePage();
        $prod = AmzProduct::query()->updateOrCreate([
            'asin' => $this->getAsin()
        ], $data);
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
        dd("crawlFail", $url, $requestException, $foundOnUrl);
    }

    protected function parsePage()
    {
        // TODO: Implement parsePage() method.
    }

    protected function getImages(DOMDocument $doc): array
    {
        //todo need complete it
        $elements = $doc->getElementsByTagName('script');
        /** @var DOMDocument $element */
        foreach ($elements as $element) {
            $str = $element->nodeValue;
            if (Str::contains($str, 'ImageBlockATF')) {
                $str = str_replace(['\\n', '\\r', PHP_EOL], '', $str);
                $str = str_replace([
                    'P.when(\'A\').register("ImageBlockATF", function(A){var data = ',
                    '\')};A.trigger(\'P.AboveTheFold\'); // trigger ATF event.return data;});',
                ], '', $str);
                $json = str_replace([
                    '\'initial\'',
                    '\'holderRatio\'',
                ], [
                    '"initial"',
                    '"holderRatio"',
                ], $str);

                dd($json, json_decode($json), json_last_error(), json_last_error_msg());
            }
        }
        dd("done");
        return [];
    }
}
