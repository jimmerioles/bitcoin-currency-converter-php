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
     */
    protected ?\GuzzleHttp\Client $client;

    /**
     * Cache instance.
     */
    protected ?\Psr\SimpleCache\CacheInterface $cache;

    /**
     * Exchange rates array.
     *
     * @var array
     */
    protected $exchangeRates;

    /**
     * Cache key string.
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     *
     * @var string
     */
    protected static $apiEndpoint;

    /**
     * Create provider instance.
     *
     * @param Client|null         $client
     * @param CacheInterface|null $cache
     * @param integer             $cacheTTL
     */
    public function __construct(Client $client = null, CacheInterface $cache = null, protected $cacheTTL = 60)
    {
        if (is_null($client)) {
            $client = new Client();
        }

        if (is_null($cache)) {
            $cache = new Repository(new FileStore(new Filesystem(), project_root_path('cache')));
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
     * @return string
     */
    protected function fetchExchangeRates()
    {
        $response = $this->client->request('GET', self::getApiEndpoint());

        if ($response->getStatusCode() != 200) {
            throw new UnexpectedValueException("Not OK response received from API endpoint."); //TODO: add @throw in phpdoc
        }

        return (string) $response->getBody();
    }

    /**
     * Get the API endpoint.
     *
     * @return string
     */
    public static function getApiEndpoint()
    {
        return static::$apiEndpoint;
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string $rawJsonData
     * @return array
     */
    abstract protected function parseToExchangeRatesArray($rawJsonData);
}
