<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter;

use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Contracts\ProviderInterface;

class Converter
{
    /**
     * Provider instance.
     */
    protected ProviderInterface $provider;

    /**
     * Create new Converter instance.
     */
    public function __construct(ProviderInterface $provider = null)
    {
        $this->provider = $provider ?? new CoinbaseProvider();
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
     */
    protected function getRate(string $currencyCode): float
    {
        return $this->provider->getRate($currencyCode);
    }

    /**
     * Compute currency value.
     */
    protected function computeCurrencyValue(int|float $btcAmount, int|float $rate): int|float
    {
        return $btcAmount * $rate;
    }

    /**
     * Format value based on currency.
     */
    protected function formatToCurrency(string $currencyCode, float $value): float
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
     */
    protected function computeBtcValue(int|float $amount, int|float $rate): int|float
    {
        return $amount / $rate;
    }
}
