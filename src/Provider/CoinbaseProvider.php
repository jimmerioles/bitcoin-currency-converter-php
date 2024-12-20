<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class CoinbaseProvider extends AbstractProvider
{
    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     */
    protected static string $apiEndpoint = 'https://api.coinbase.com/v2/exchange-rates?currency=BTC';

    /**
     * Cache key to use when storing and retrieving from cache.
     */
    protected string $cacheKey = 'coinbase-cache-key';

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

        return $arrayData['data']['rates'];
    }
}
