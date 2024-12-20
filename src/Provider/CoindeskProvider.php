<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class CoindeskProvider extends AbstractProvider
{
    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     */
    protected static string $apiEndpoint = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    /**
     * Cache key to use when storing and retrieving from cache.
     */
    protected string $cacheKey = 'coindesk-cache-key';

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @return array<string, int|float>
     * @throws InvalidArgumentException
     */
    protected function parseToExchangeRatesArray(string $rawJsonData): array
    {
        $arrayData = json_decode($rawJsonData, true);

        if (!is_array($arrayData)) {
            throw new InvalidArgumentException('Invalid JSON data provided.');
        }

        $exchangeRatesArray = [];

        foreach ($arrayData['bpi'] as $value) {
            $exchangeRatesArray[$value['code']] = $value['rate_float'];
        }

        return $exchangeRatesArray;
    }
}
