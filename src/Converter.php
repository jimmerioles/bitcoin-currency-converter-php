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

        $value = $this->compute($btcAmount, $rate);

        return $this->format($currencyCode, $value);
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
    protected function compute(int|float $amount, int|float $rate, bool $toBtc = false): int|float
    {
        if ($toBtc) {
            return $amount / $rate;
        }

        return $amount * $rate;
    }

    /**
     * Format value based on currency.
     */
    protected function format(string $currencyCode, float $value): float
    {
        return format_to_currency($currencyCode, $value);
    }

    /**
     * Convert currency amount to Bitcoin.
     */
    public function toBtc(float $amount, string $currencyCode): float
    {
        $rate = $this->getRate($currencyCode);

        $value = $this->compute($amount, $rate, toBtc: true);

        return $this->format('BTC', $value);
    }
}
