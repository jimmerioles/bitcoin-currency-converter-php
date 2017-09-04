<?php

namespace Jimmerioles\BitcoinCurrencyConverter;

use GuzzleHttp\Client;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class Converter
{
    /**
     * Provider instance.
     *
     * @var Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface
     */
    protected $provider;

    /**
     * Create Converter instance.
     *
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider = null)
    {
        if (is_null($provider)) {
            $provider = new CoinbaseProvider;
        }

        $this->provider = $provider;
    }

    /**
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string $currencyCode
     * @param  float  $btcAmount
     * @return float
     */
    public function toCurrency($currencyCode, $btcAmount)
    {
        $rate = $this->getRate($currencyCode);

        $value = $this->computeCurrencyValue($btcAmount, $rate);

        return $this->formatToCurrency($currencyCode, $value);
    }

    /**
     * Get rate of currency.
     *
     * @param  string $currencyCode
     * @return float
     */
    protected function getRate($currencyCode)
    {
        return $this->provider->getRate($currencyCode);
    }

    /**
     * Compute currency value.
     *
     * @param  float $btcAmount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    protected function computeCurrencyValue($btcAmount, $rate)
    {
        if (! is_numeric($btcAmount)) {
            throw new InvalidArgumentException("Argument \$btcAmount should be numeric, '{$btcAmount}' given.");
        }

        return $btcAmount * $rate;
    }

    /**
     * Format value based on currency.
     *
     * @param  string $currencyCode
     * @param  float  $value
     * @return float
     */
    protected function formatToCurrency($currencyCode, $value)
    {
        return format_to_currency($currencyCode, $value);
    }

    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string $currency
     * @return float
     */
    public function toBtc($amount, $currencyCode)
    {
        $rate = $this->getRate($currencyCode);

        $value = $this->computeBtcValue($amount, $rate);

        return $this->formatToCurrency('BTC', $value);
    }

    /**
     * Compute Bitcoin value.
     *
     * @param  float $amount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    protected function computeBtcValue($amount, $rate)
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException("Argument \$amount should be numeric, '{$amount}' given.");
        }

        return $amount / $rate;
    }
}
