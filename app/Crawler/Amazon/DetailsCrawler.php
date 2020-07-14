<?php

namespace App\Crawler\Amazon;

use App\Models\AmzProduct;
use DOMDocument;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;

class DetailsCrawler extends Amazon
{
    protected function parsePage()
    {
        $prod = AmzProduct::query()->firstOrCreate(['asin'=> $this->getAsin()]);
        if (is_null($prod)) {
            return null;
        }

        $doc = $this->doc;

        $review = trim(optional($doc->getElementById('acrCustomerReviewText'))->nodeValue);
        $stars = trim(optional($doc->getElementById('acrPopover'))->getAttribute('title'));
        $featureDesc = preg_replace('/\s\s+/', '', trim(optional($doc->getElementById('featurebullets_feature_div'))->nodeValue));
        $desc = trim(optional($doc->getElementById('productDescription'))->nodeValue);
        $title = str_replace(PHP_EOL, '', optional($doc->getElementById('productTitle'))->nodeValue);
        $authors = optional($doc->getElementById('bylineInfo'))->nodeValue;
        //$images = $this->getImages($doc);
        $images = null;

        $data = [
            'asin' => $this->getAsin(),
            'currency' => 'EUR',
            'itemDetailUrl' => $this->getShopUrl(),
            //'sellerOffersUrl'=> "https://www.amazon.com/gp/offer-listing/B07XZMHTL5",
        ];

        if ($title) {
            $data['title'] = $title;
        }
        if ($desc) {
            $data['description'] = $desc;
        }
        if ($featureDesc) {
            $data['featureDescription'] = $featureDesc;
        }
        if ($authors) {
            $data['authors'] = $authors;
        }
        if ($stars) {
            $data['stars'] = $stars;
        }
        if ($review) {
            $data['review'] = $review;
        }
        if ($images) {
            $data['images'] = $images;
        }

        return $data;
    }


    protected function getImages(DOMDocument $doc): array
    {
        $elements = $doc->getElementsByTagName('script');
        /** @var DOMDocument $element */
        foreach ($elements as $element) {
            $str = $element->nodeValue;
            if (Str::contains($str, 'ImageBlockATF')) {
                /*$jquery = new Dom();$jquery->load($doc->saveHTML($element));*/

                $jquery = new Dom();
                $jquery->load($doc->saveHTML($element));

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
