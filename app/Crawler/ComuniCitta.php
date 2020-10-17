<?php

namespace App\Crawler;

use App\Models\Address;
use Exception;
use GuzzleHttp\Exception\RequestException;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class ComuniCitta extends CrawlObserver
{
    protected Address $address;

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $jquery = new Dom();
        try {
            $jquery->load($response->getBody());
            /*$table = $jquery->find('table.table-bordered');
            $row = $table->find('tbody')[0]->find('tr')[0];
            $td = $row->find('td')[2];
            $cap = $td->find('a');
            preg_match('/([0-9]{5})/', $cap->outerHtml, $res);
            dd("Cap: ". Arr::first($res));*/

            $zip = $jquery->find('#display_zip');
            $country = $jquery->find('#display_county')[0];

            $this->address->cap = trim($zip->text) ?? null;
            $this->address->provincia = trim($country->text) ?? null;
            $this->address->save();

            echo "\tCountry: {$country->text} - Zip: {$zip->text}" . PHP_EOL;
        } catch (Exception $e) {
            dump($e->getMessage());
            report($e);
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
        dump("error", $status, $msg);
        report($requestException);
    }
}
