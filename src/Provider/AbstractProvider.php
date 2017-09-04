<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use GuzzleHttp\Client;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * GuzzleHttp client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     *
     * @var string
     */
    protected $apiEndpoint = '';

    /**
     * Create provider instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        if (is_null($client)) {
            $client = new Client;
        }

        $this->client = $client;
    }

    /**
     * Get rate of currency.
     *
     * @param  string $currencyCode
     * @return float
     */
    public function getRate($currencyCode)
    {
        if (! is_currency_code($currencyCode)) {
            throw new InvalidArgumentException("Argument \$currencyCode not valid currency code, '{$currencyCode}' given.");
        }

        $exchangeRates = $this->getExchangeRates();

        if (! $this->isSupportedByProvider($currencyCode)) {
            throw new InvalidArgumentException("Argument \$currencyCode '{$currencyCode}' not supported by provider.");
        }

        return $exchangeRates[strtoupper($currencyCode)];
    }

    /**
     * Check if currency code supported by provider.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    protected function isSupportedByProvider($currencyCode)
    {
        return in_array(strtoupper($currencyCode), array_keys($this->exchangeRates));
    }

    /**
     * Get exchange rates in associative array.
     *
     * @return array
     */
    protected function getExchangeRates()
    {
        if (empty($this->exchangeRates)) {
            $this->setExchangeRates($this->retrieveExchangeRates());
        }

        return $this->exchangeRates;
    }

    /**
     * Set exchange rates.
     *
     * @param array $exchangeRatesArray
     */
    protected function setExchangeRates($exchangeRatesArray)
    {
        $this->exchangeRates = $exchangeRatesArray;
    }

    /**
     * Retrieve exchange rates.
     *
     * @return array
     */
    protected function retrieveExchangeRates()
    {
        return $this->parseToExchangeRatesArray($this->fetchExchangeRates());
    }

    /**
     * Fetch exchange rates json data from API endpoint.
     *
     * @return string|json
     */
    protected function fetchExchangeRates()
    {
        $response = $this->client->request('GET', $this->apiEndpoint);

        if ($response->getStatusCode() != 200) {
            throw new UnexpectedValueException("Not OK response received from API endpoint.");
        }

        return $response->getBody();
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string|json $rawJsonData
     * @return array
     */
    abstract protected function parseToExchangeRatesArray($rawJsonData);
}
