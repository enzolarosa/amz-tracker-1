<?php

namespace App\Crawler\Amazon;

use App\Jobs\Amazon\SearchJob;
use App\Jobs\AmazonProductJob;
use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\User;
use DOMDocument;
use Exception;
use GuzzleHttp\Exception\RequestException;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class SearchCrawler extends CrawlObserver
{
    const WAIT_CRAWLER = 30;

    protected DOMDocument $doc;
    protected ResponseInterface $response;
    protected UriInterface $uri;

    protected string $currency;
    protected string $country;
    protected ?User $user = null;

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

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        preg_match('/([A-Z0-9]{10})/', $url->getPath(), $prod, PREG_OFFSET_CAPTURE);

        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $jquery = new Dom();
        $jquery->load($doc->saveHTML());

        $asins = $jquery->find('div.s-asin');
        $waitSec = self::WAIT_CRAWLER;

        foreach ($asins as $k => $asin) {
            $asin = $asin->{'data-asin'};
            if ($this->getUser()) {
                $product = AmzProduct::query()->firstOrCreate(['asin' => $asin]);
                AmzProductUser::query()->updateOrCreate([
                    'user_id' => $this->getUser()->id,
                    'amz_product_id' => $product->id
                ], [
                    'enabled' => true
                ]);
            }
            $job = new AmazonProductJob($asin);
            dispatch($job)->delay(now()->addSeconds($waitSec));

            $waitSec += self::WAIT_CRAWLER;
        }

        $pagination = $jquery->find('ul.a-pagination');
        $next = $pagination->find('li.a-last');
        $href = optional($next->find('a'))->href;

        if ($href) {
            $link = "{$url->getScheme()}://{$url->getHost()}$href";
            $job = new SearchJob('amz-crawler', ['IT'], $link);
            $job->setUser($this->getUser());
            dispatch($job)->delay(now()->addSeconds($waitSec * 2));
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

        $link = "{$url->getScheme()}://{$url->getHost()}{$url->getPath()}?{$url->getQuery()}";
        $job = new SearchJob('amz-crawler', ['IT'], $link);
        $job->setUser($this->getUser());
        dispatch($job)->delay(now()->addHours(4));

        throw new Exception($msg);
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
}
