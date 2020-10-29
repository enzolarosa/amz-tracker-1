<?php

namespace App\Crawler\Amazon;

use App\Common\Constants;
use App\Jobs\Amazon\SearchJob;
use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\SearchList;
use DOMDocument;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class SearchCrawler extends CrawlObserver
{
    const WAIT_CRAWLER = 60;
    protected DOMDocument $doc;
    protected ResponseInterface $response;
    protected UriInterface $uri;

    protected string $currency;
    protected string $country;
    protected SearchList $searchList;
    protected $batchId;

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        if (!is_null($this->batchId)) {
            $batch = Bus::findBatch($this->batchId);
        }

        preg_match('/([A-Z0-9]{10})/', $url->getPath(), $prod, PREG_OFFSET_CAPTURE);

        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $jquery = new Dom();
        $jquery->loadStr($doc->saveHTML());

        $asins = $jquery->find('div.s-asin');

        foreach ($asins as $k => $asin) {
            $asin = $asin->{'data-asin'};
            if ($this->getSearchList()->trackable) {
                $product = AmzProduct::query()->firstOrCreate(['asin' => $asin]);
                AmzProductUser::query()->updateOrCreate([
                    'trackable_id' => $this->getSearchList()->trackable->id,
                    'trackable_type' => get_class($this->getSearchList()->trackable),
                    'amz_product_id' => $product->id
                ], [
                    'enabled' => true
                ]);
            }

            if (!is_null($this->batchId)) {
                $job = new AmazonProductJob($asin, $this->batchId);
                $batch->add([$job]);
            } else {
                dispatch(new AmazonProductJob($asin));
            }
        }

        try {
            $pagination = $jquery->find('ul.a-pagination');
            $next = $pagination->find('li.a-last');
            $href = optional($next->find('a'))->href;

            if ($href) {
                $link = "{$url->getScheme()}://{$url->getHost()}$href";
                $job = new SearchJob($this->searchList, ['IT'], $link);

                if (!is_null($this->batchId)) {
                    $batch->add([$job]);
                } else {
                    dispatch($job);
                }
            }
        } catch (Exception $exception) {
            report($exception);
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        $status = $requestException->getResponse()->getStatusCode();
        $msg = sprintf(
            'The `%s` link have some issues: status code `%s` message: %s',
            $requestException->getRequest()->getUri(),
            $status,
            $requestException->getMessage(),
        );

        $doc = new DOMDocument();
        @$doc->loadHTML($requestException->getResponse()->getBody());
        $jquery = new Dom();
        $jquery->loadStr($doc->saveHTML());

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
            // $secondsRemaining = $response->header('Retry-After');
            $secondsRemaining = self::WAIT_CRAWLER;
            Cache::put(Constants::getAmzHttpLimitKey(), now()->addSeconds($secondsRemaining)->timestamp, $secondsRemaining);
            $report = false;
        }

        if ($report) {
            report(new Exception($msg));
        }
    }

    /**
     * @return SearchList
     */
    public function getSearchList(): SearchList
    {
        return $this->searchList;
    }

    /**
     * @param SearchList $searchList
     */
    public function setSearchList(SearchList $searchList): void
    {
        $this->searchList = $searchList;
    }

    /**
     * @return mixed
     */
    public function getBatchId()
    {
        return $this->batchId;
    }

    /**
     * @param mixed $batchId
     */
    public function setBatchId($batchId): void
    {
        $this->batchId = $batchId;
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
}
