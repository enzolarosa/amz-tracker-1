<?php

namespace App\Crawler\Amazon;

use DOMElement;
use Illuminate\Support\Arr;

class OffersCrawler extends Amazon
{
    protected function parsePage()
    {
        $offers = $this->getElementsByClassName('olpOffer', 'div');
        $sellers = [];
        /** @var DOMElement $offer */
        foreach ($offers as $offer) {
            $price = trim(optional($this->getElementsByClassName('olpOfferPrice', 'span', $offer)[0])->nodeValue);
            $priceParsed = (float)str_replace([$this->getCurrency(), ','], ['', '.'], $price);

            $pricePerUnit = trim(optional($this->getElementsByClassName('olpOfferPrice', 'span', $offer)[0])->nodeValue);
            $offerCondition = preg_replace('/\s\s+/', '', optional($this->getElementsByClassName('olpCondition', 'span', $offer)[0])->nodeValue);

            $shippingInfo = preg_replace('/\s\s+/', '', optional($this->getElementsByClassName('olpShippingInfo', 'p', $offer)[0])->nodeValue);

            $prime = optional($this->getElementsByClassName('a-icon-prime', 'i', $offer)) ? true : false;
            /** @var DOMElement $sellerNameEl */
            $sellerNameEl = $this->getElementsByClassName('olpSellerName', 'h3', $offer)[0];
            /** @var DOMElement $img */
            $img = $sellerNameEl->getElementsByTagName('img')->item(0);
            /** @var DOMElement $a */
            $a = $sellerNameEl->getElementsByTagName('a')->item(0);

            $sellerName = $img->getAttribute('alt');
            $shopUrl = $a->getAttribute('href');

            if (empty($shippingInfo) || $shippingInfo == '') {
                //$shippingInfos = $this->getElementsByClassName('olpPriceColumn', 'div', $offer);
                $shippingInfo = 'shipping info not included';
            }
            if (!$offerCondition) {
                $offerCondition = 'condition unknown';
            }

            $sellers[] = [
                'price' => $price,
                'priceParsed' => $priceParsed,
                'condition' => $offerCondition,
                'sellerName' => $sellerName,
                'prime' => $prime,
                'shippingInfo' => $shippingInfo,
                'shopUrl' => $shopUrl . '?tag=' . env('AMZ_PARTNER'),
                'pricePerUnit' => $pricePerUnit
            ];
        }

        return [
            'asin' => $this->getAsin(),
            'sellers' => $sellers,
        ];
    }
}
