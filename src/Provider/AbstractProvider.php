<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Psr\SimpleCache\CacheInterface;
use Illuminate\Filesystem\Filesystem;
use Jimmerioles\BitcoinCurrencyConverter\Contracts\ProviderInterface;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * Client instance.
     */
    protected ClientInterface $client;

    /**
     * Cache instance.
     */
    protected CacheInterface $cache;

    /**
     * Exchange rates array.
     *
     * @var array<string, int|float>
     */
    protected array $exchangeRates = [];

    /**
     * Cache key string.
     */
    protected string $cacheKey;

    /**
     * Provider's exchange rates API endpoint, with 1 BTC as base.
     */
    protected static string $apiEndpoint;

    /**
     * Create provider instance.
     */
    public function __construct(ClientInterface $client = null, CacheInterface $cache = null, protected int $cacheTTL = 60)
    {
        $this->client = $client ?? new Client();
        $this->cache = $cache ?? new Repository(new FileStore(new Filesystem(), project_root_path('cache')));
    }

    /**
     * Get rate of currency.
     *
     * @throws InvalidArgumentException
     */
    public function getRate(string $currencyCode): float
    {
        if (! is_currency_code($currencyCode)) {
            throw new InvalidArgumentException("Argument passed not a valid currency code, '" . $currencyCode . "' given.");
        }

        $exchangeRates = $this->getExchangeRates();

        if (! $this->isSupportedByProvider($currencyCode)) {
            throw new InvalidArgumentException('Argument $currencyCode \'' . $currencyCode . "' not supported by provider.");
        }

        return (float) $exchangeRates[strtoupper($currencyCode)];
    }

    /**
     * Check if currency code supported by provider.
     */
    protected function isSupportedByProvider(string $currencyCode): bool
    {
        return in_array(strtoupper($currencyCode), array_keys($this->exchangeRates), true);
    }

    /**
     * Get exchange rates in associative array.
     *
     * @return array<string, int|float>
     */
    protected function getExchangeRates(): array
    {
        if ($this->exchangeRates === []) {
            $this->setExchangeRates($this->retrieveExchangeRates());
        }

        return $this->exchangeRates;
    }

    /**
     * Set exchange rates.
     *
     * @param array<string, int|float> $exchangeRatesArray
     */
    protected function setExchangeRates(array $exchangeRatesArray): void
    {
        $this->exchangeRates = $exchangeRatesArray;
    }

    /**
     * Retrieve exchange rates.
     *
     * @return array<string, int|float>
     */
    protected function retrieveExchangeRates(): array
    {
        if ($this->cache->has($this->cacheKey)) {
            $cachedExchangeRates = $this->cache->get($this->cacheKey);

            if (is_array($cachedExchangeRates)) {
                return $cachedExchangeRates;
            }
        }

        $exchangeRatesArray = $this->parseToExchangeRatesArray($this->fetchExchangeRates());

        $this->cache->set($this->cacheKey, $exchangeRatesArray, $this->cacheTTL);

        return $exchangeRatesArray;
    }

    /**
     * Fetch exchange rates json data from API endpoint.
     *
     * @throws UnexpectedValueException
     */
    protected function fetchExchangeRates(): string
    {
        $response = $this->client->request('GET', self::getApiEndpoint());

        if ($response->getStatusCode() !== 200) {
            throw new UnexpectedValueException("Not OK response received from API endpoint.");
        }

        return (string) $response->getBody();
    }

    /**
     * Get the API endpoint.
     */
    public static function getApiEndpoint(): string
    {
        return static::$apiEndpoint;
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @return array<string, int|float>
     */
    abstract protected function parseToExchangeRatesArray(string $rawJsonData): array;
}
