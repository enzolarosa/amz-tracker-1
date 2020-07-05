<?php

namespace App\Jobs;

use App\Crawler\AmazonItObserver;
use App\Models\PriceTrace;
use App\Services\AmzTracker;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Spatie\Crawler\Crawler;
use Spatie\RateLimitedMiddleware\RateLimited;


use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\Configuration;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResource;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\ProductAdvertisingAPIClientException;

class AmzChecker extends Job
{
    protected PriceTrace $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('amz-checker');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $amz = new AmzTracker(env('AMZ_PARTNER'));
        $searchItemsResponse = $amz->search($key = $this->getProduct()->product_id);

        # Parsing the response
        if ($searchItemsResponse && $searchItemsResponse->getSearchResult() != null) {
            echo 'Printing first item information in SearchResult:', PHP_EOL;
            $item = $searchItemsResponse->getSearchResult()->getItems()[0];
            if ($item != null) {
                if ($item->getASIN() != null) {
                    echo "ASIN: ", $item->getASIN(), PHP_EOL;
                }
                if ($item->getDetailPageURL() != null) {
                    echo "DetailPageURL: ", $item->getDetailPageURL(), PHP_EOL;
                }
                if ($item->getItemInfo() != null && $item->getItemInfo()->getTitle() != null && $item->getItemInfo()->getTitle()->getDisplayValue() != null) {
                    echo "Title: ", $item->getItemInfo()->getTitle()->getDisplayValue(), PHP_EOL;
                }
                if ($item->getOffers() != null && $item->getOffers() != null && $item->getOffers()->getListings() != null && $item->getOffers()->getListings()[0]->getPrice() != null && $item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount() != null) {
                    echo "Buying price: ", $item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount(), PHP_EOL;
                }
            }
        }
        if ($searchItemsResponse && $searchItemsResponse->getErrors() != null) {
            echo PHP_EOL, 'Printing Errors:', PHP_EOL, 'Printing first error object from list of errors', PHP_EOL;
            echo 'Error code: ', $searchItemsResponse->getErrors()[0]->getCode(), PHP_EOL;
            echo 'Error message: ', $searchItemsResponse->getErrors()[0]->getMessage(), PHP_EOL;
        }
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(3)
            ->everySeconds(10)
            ->releaseAfterSeconds(20);

        return [$rateLimitedMiddleware];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return Carbon::now()->addDay();
    }

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
}
