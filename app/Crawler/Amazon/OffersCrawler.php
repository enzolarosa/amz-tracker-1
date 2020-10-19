<?php

namespace App\Crawler\Amazon;

use App\Models\AmzProduct;
use PHPHtmlParser\Dom;

class OffersCrawler extends Amazon
{
    protected function parsePage()
    {
        $prod = AmzProduct::query()->firstOrCreate(['asin' => $this->getAsin()]);
        if (is_null($prod)) {
            return null;
        }

        $jquery = new Dom();
        $jquery->load($this->doc->saveHTML());

        $offers = $jquery->find('div.olpOffer');
        $sellers = [];

        foreach ($offers as $k => $offer) {
            $price = trim(optional($offer->find('span.olpOfferPrice')[0])->text);
            $priceParsed = (float)str_replace([$this->getCurrency(), '.', ','], ['', '', '.'], $price);
            $pricePerUnit = trim(optional($offer->find('span.olpOfferPrice')[0])->text);
            $offerCondition = trim(preg_replace('/\s\s+/', '', optional($offer->find('span.olpCondition')[0])->text));
            $shippingPrice = optional($offer->find('span.olpShippingPrice')[0])->text;
            $shippingParsed = (float)str_replace([$this->getCurrency(), '.', ','], ['', '', '.'], $price);
            $shippingInfo = optional($offer->find('span.olpShippingPriceText')[0])->text;

            $prime = optional($offer->find('i.a-icon-prime')[0]) ? true : false;
            $sellerNameEl = optional($offer->find('h3.olpSellerName')[0]);

            if ($sellerNameEl) {
                $sellerName = trim(optional($sellerNameEl->find('a')[0])->text);
                $shopUrl = trim(optional($sellerNameEl->find('a')[0])->href);
            }

            if (empty($shippingInfo) || $shippingInfo == '') {
                $shippingInfo = trim(preg_replace('/\s\s+/', '', optional($offer->find('a.olpFbaPopoverTrigger')[0])->text));
                if (empty($shippingInfo) || $shippingInfo == '') {
                    $shippingInfo = 'shipping info not included';
                }
            }
            if (!$offerCondition) {
                $offerCondition = 'condition unknown';
            }

            $sellers[$k] = [
                'price' => $price,
                'priceParsed' => $priceParsed,
                'condition' => $offerCondition,
                'sellerName' => $sellerName ?? 'seller unknown',
                'prime' => $prime,
                'shippingPrice' => $shippingPrice,
                'shippingParsed' => $shippingParsed,
                'shippingInfo' => $shippingInfo,
                'shopUrl' => $shopUrl ?? 'seller unknown',
                'pricePerUnit' => $pricePerUnit
            ];
        }

        return [
            'asin' => $this->getAsin(),
            'sellers' => $sellers,
        ];
    }
}
