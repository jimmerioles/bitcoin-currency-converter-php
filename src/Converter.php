<?php

namespace Jimmerioles\BitcoinCurrencyConverter;

use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class Converter
{
    /**
     * Create new Converter instance.
     */
    public function __construct(protected ?ProviderInterface $provider = null)
    {
        if (is_null($provider)) {
            $provider = new CoinbaseProvider();
        }

        $this->provider = $provider;
    }

    /**
     * Convert Bitcoin amount to a specific currency.
     */
    public function toCurrency(string $currencyCode, float $btcAmount): float
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
     * @throws InvalidArgumentException
     */
    protected function computeCurrencyValue($btcAmount, $rate): int|float
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
     */
    protected function formatToCurrency($currencyCode, $value): float
    {
        return format_to_currency($currencyCode, $value);
    }

    /**
     * Convert currency amount to Bitcoin.
     */
    public function toBtc(float $amount, string $currencyCode): float
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
     * @throws InvalidArgumentException
     */
    protected function computeBtcValue($amount, $rate): int|float
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException("Argument \$amount should be numeric, '{$amount}' given.");
        }

        return $amount / $rate;
    }
}
