<?php

namespace App\Crawler\Amazon;

use DOMDocument;
use DOMElement;
use DOMXPath;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class Amazon extends CrawlObserver
{
    protected DOMDocument $doc;
    protected ResponseInterface $response;

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $doc = new DOMDocument();
        $finder = new DomXPath($doc);

        @$doc->loadHTML($response->getBody());

        $this->doc = $doc;
        $this->response = $response;

        $data = $this->parsePage();
        dd(get_class(), $data);
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

    /**
     * @param $className
     * @param null $tagName
     * @return array
     */
    protected function getElementsByClassName($className, $tagName = null)
    {
        if ($tagName) {
            $elements = $this->doc->getElementsByTagName($tagName);
        } else {
            $elements = $this->doc->getElementsByTagName("*");
        }
        $matched = [];
        /** @var DOMElement $element */
        foreach ($elements as $element) {
            $class = $element->getAttribute('class');
            if (Str::contains($class, $className)) {
                $matched[] = $element;
            }
        }
        return $matched;
    }

}
