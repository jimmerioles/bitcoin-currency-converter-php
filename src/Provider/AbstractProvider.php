<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use GuzzleHttp\Client;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Psr\SimpleCache\CacheInterface;
use Illuminate\Filesystem\Filesystem;
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
     * Directory to store file cache relative to project root directory.
     *
     * @var string
     */
    protected $cacheDir = 'cache';

    /**
     * Cache's time to live in minutes.
     *
     * @var integer
     */
    protected $cacheTTL = 60;

    /**
     * Create provider instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client = null, CacheInterface $cache = null)
    {
        if (is_null($client)) {
            $client = new Client;
        }

        if (is_null($cache)) {
            $cache = new Repository(new FileStore(new Filesystem, project_root_path($this->cacheDir)));
        }

        $this->client = $client;
        $this->cache = $cache;
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
            throw new InvalidArgumentException("Argument passed not a valid currency code, '{$currencyCode}' given.");
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
        if ($this->cache->has($this->cacheKey)) {
            return $this->cache->get($this->cacheKey);
        }

        $exchangeRatesArray = $this->parseToExchangeRatesArray($this->fetchExchangeRates());

        $this->cache->set($this->cacheKey, $exchangeRatesArray, $this->cacheTTL);

        return $exchangeRatesArray;
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
