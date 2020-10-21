<?php

namespace App\Crawler\Amazon;

use App\Common\Constants;
use App\Models\AmzProduct;
use App\Models\AmzProductQueue;
use App\Models\Setting;
use DOMDocument;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class Amazon extends CrawlObserver
{
    const WAIT_CRAWLER = 60;

    protected DOMDocument $doc;
    protected ResponseInterface $response;
    protected UriInterface $uri;

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
        $this->uri = $url;

        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->getAsin()]);

        $data = $this->parsePage();
        if (!is_null($data)) {
            if (!is_null($prod->current_price)) {
                $prod->update(['previous_price' => $prod->current_price]);
            }
            $prod->update($data);
        }

        $queue = AmzProductQueue::query()->firstOrCreate(['amz_product_id' => $prod->id]);
        try {
            $queue->delete();
        } catch (Exception $exception) {
            report($exception);
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
        $status = $requestException->getResponse()->getStatusCode();

        $msg = sprintf(
            'The `%s` link have some issues: status code `%s` message: %s',
            $requestException->getRequest()->getUri(),
            $status,
            $requestException->getMessage(),
        );

        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->getAsin()]);
        if ($status === Response::HTTP_NOT_FOUND) {
            $prod->update(['enabled' => false]);
        }

        $doc = new DOMDocument();
        @$doc->loadHTML($requestException->getResponse()->getBody());
        $jquery = new Dom();
        $jquery->load($doc->saveHTML());

        $title = trim(optional(optional($jquery->find('title'))[0])->text);
        $report = true;
        if (
            ($status !== Response::HTTP_OK && $status !== Response::HTTP_NOT_FOUND)
            || Str::contains($title, 'Robot Check')
            || Str::contains($title, 'CAPTCHA')
            || Str::contains($title, 'Toutes nos excuses')
            || Str::contains($title, 'Tut uns Leid!')
            || Str::contains($title, 'Service Unavailable Error')
            || Str::contains($title, 'Ci dispiace')
        ) {
            Setting::store('amz-wait', true, now()->addMinutes(Constants::$WAIT_AMZ_HTTP_ERROR));
            // $secondsRemaining = $response->header('Retry-After');
            $secondsRemaining = self::WAIT_CRAWLER;
            Cache::put(Constants::getAmzHttpLimitKey(), now()->addSeconds($secondsRemaining)->timestamp, $secondsRemaining);
            $report = false;
        }

        $queue = AmzProductQueue::query()->firstOrCreate(['amz_product_id' => $prod->id]);

        try {
            $queue->delete();
        } catch (Exception $exception) {
            report($exception);
        }

        if ($report) {
            report(new Exception($msg));
        }
    }

    protected function parsePage()
    {
        // TODO: Implement parsePage() method.
    }
}
