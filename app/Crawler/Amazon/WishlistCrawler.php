<?php

namespace App\Crawler\Amazon;

use App\Common\Constants;
use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\User;
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

class WishlistCrawler extends CrawlObserver
{
    const WAIT_CRAWLER = 60;

    protected DOMDocument $doc;
    protected ResponseInterface $response;
    protected UriInterface $uri;

    protected string $currency;
    protected string $country;
    protected ?User $user = null;
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

        $products = $jquery->find('li.g-item-sortable');

        foreach ($products as $k => $product) {
            $aNode = $product->find('a.a-link-normal');
            $link = $aNode->href;

            preg_match('/([A-Z0-9]{10})/', $link, $prod, PREG_OFFSET_CAPTURE);
            $asin = optional(optional($prod)[0])[0];

            if ($this->getUser()) {
                $product = AmzProduct::query()->firstOrCreate(['asin' => $asin]);
                AmzProductUser::query()->updateOrCreate([
                    'user_id' => $this->getUser()->id,
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
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
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
