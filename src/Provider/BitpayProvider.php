<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class BitpayProvider extends AbstractProvider
{
    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     *
     * @var string
     */
    protected static $apiEndpoint = 'https://bitpay.com/api/rates';

    /**
     * Cache key to use when storing and retrieving from cache.
     *
     * @var string
     */
    protected $cacheKey = 'bitpay-cache-key';

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string $rawJsonData
     */
    protected function parseToExchangeRatesArray($rawJsonData): array
    {
        $arrayData = json_decode($rawJsonData, true);

        if (!is_array($arrayData)) {
            throw new InvalidArgumentException('Invalid JSON data provided.');
        }

        $exchangeRatesArray = [];

        foreach ($arrayData as $data) {
            $exchangeRatesArray[$data['code']] = $data['rate'];
        }

        return $exchangeRatesArray;
    }
}
