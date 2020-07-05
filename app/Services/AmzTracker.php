<?php

namespace App\Services;

use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\ProductAdvertisingAPIClientException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResource;
use Amazon\ProductAdvertisingAPI\v1\Configuration;
use Exception;
use GuzzleHttp\Client;

class AmzTracker
{
    protected Client $client;
    protected Configuration $config;
    protected string $partnerTag;
    protected DefaultApi $api;

    public function __construct(string $partnerTag)
    {
        $this->partnerTag = $partnerTag;
        $this->client = new Client();
        $this->config = new Configuration();
        $this->config->setAccessKey(env('AMZ_KEY'));
        $this->config->setSecretKey(env('AMZ_SECRET'));
        $this->config->setHost('webservices.amazon.it');
        $this->config->setRegion(env('AMZ_REGION'));
        $this->api = new DefaultApi($this->client, $this->config);
    }

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
