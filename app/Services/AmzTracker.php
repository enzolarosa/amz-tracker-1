<?php

namespace App\Services;

use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\ProductAdvertisingAPIClientException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResource;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResponse;
use Amazon\ProductAdvertisingAPI\v1\Configuration;
use App\Logging\GuzzleLogger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;

class AmzTracker
{
    protected Client $client;
    protected Configuration $config;
    protected string $partnerTag;
    protected DefaultApi $api;

    /**
     * AmzTracker constructor.
     * @param string $partnerTag
     */
    public function __construct(string $partnerTag)
    {
        $handler = HandlerStack::create();

        $handler->push(Middleware::log(new Logger('ExtGuzzleLogger'),
            (new GuzzleLogger('{req_body} - {res_body}'))->setProvider('amz-api-out')
        ));

        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            $requestId = Arr::first($request->getHeader('X-Request-ID')) ?? (string)Str::uuid();

            return $request->withAddedHeader('X-Request-ID', $requestId);
        }));

        $this->client = new Client(['verify' => config('app.env') !== 'local', 'handler' => $handler, 'timeout' => 60]);

        $this->partnerTag = $partnerTag;
        $this->config = new Configuration();
        $this->config->setAccessKey(env('AMZ_KEY'));
        $this->config->setSecretKey(env('AMZ_SECRET'));
        $this->config->setHost('webservices.amazon.it');
        $this->config->setRegion(env('AMZ_REGION'));
        $this->api = new DefaultApi($this->client, $this->config);
    }

    /**
     * @param string $keyword
     * @return SearchItemsResponse|bool
     */
    public function search(string $keyword)
    {
        $searchIndex = "All";
        $itemCount = 1;

        $resources = [
            SearchItemsResource::ITEM_INFOTITLE,
            SearchItemsResource::OFFERSLISTINGSPRICE
        ];

        $searchItemsRequest = new SearchItemsRequest();
        $searchItemsRequest->setSearchIndex($searchIndex);
        $searchItemsRequest->setKeywords($keyword);
        $searchItemsRequest->setItemCount($itemCount);
        $searchItemsRequest->setPartnerTag($this->partnerTag);
        $searchItemsRequest->setPartnerType(PartnerType::ASSOCIATES);
        $searchItemsRequest->setResources($resources);
        $invalidPropertyList = $searchItemsRequest->listInvalidProperties();
        $length = count($invalidPropertyList);

        if ($length > 0) {
            foreach ($invalidPropertyList as $invalidProperty) {
                dump($invalidProperty);
            }
            return false;
        }

        try {
            return $this->api->searchItems($searchItemsRequest);
        } catch (ApiException $exception) {
            if ($exception->getResponseObject() instanceof ProductAdvertisingAPIClientException) {
                $errors = $exception->getResponseObject()->getErrors();
                foreach ($errors as $error) {
                    dump("Error Type: ", $error->getCode());
                    dump("Error Message: ", $error->getMessage());
                }
            } else {
                dump("Error response body: ", $exception->getResponseBody());
            }
            report($exception);
        } catch (Exception $exception) {
            report($exception);
            dump("Error Message: ", $exception->getMessage());
        }
        return false;
    }
}
