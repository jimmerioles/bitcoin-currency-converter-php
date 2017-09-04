<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

class CoinbaseProvider extends AbstractProvider
{
    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     *
     * @var string
     */
    protected $apiEndpoint = 'https://api.coinbase.com/v2/exchange-rates?currency=BTC';

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string|json $rawJsonData
     * @return array
     */
    protected function parseToExchangeRatesArray($rawJsonData)
    {
        $arrayData = json_decode($rawJsonData, true);

        return $arrayData['data']['rates'];
    }
}
