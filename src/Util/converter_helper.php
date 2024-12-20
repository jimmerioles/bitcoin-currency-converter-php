<?php

declare(strict_types=1);

use Jimmerioles\BitcoinCurrencyConverter\Contracts\ProviderInterface;
use Jimmerioles\BitcoinCurrencyConverter\Converter;

if (! function_exists('to_currency')) {
    /**
     * Convert Bitcoin amount to a specific currency.
     */
    function to_currency(string $currencyCode, float $btcAmount, ?ProviderInterface $provider): float
    {
        if ($provider instanceof ProviderInterface) {
            return (new Converter($provider))->toCurrency($currencyCode, $btcAmount);
        }

        return (new Converter())->toCurrency($currencyCode, $btcAmount);
    }
}

if (! function_exists('to_btc')) {
    /**
     * Convert currency amount to Bitcoin.
     */
    function to_btc(float $amount, string $currencyCode, ?ProviderInterface $provider = null): float
    {
        if ($provider instanceof ProviderInterface) {
            return (new Converter($provider))->toBtc($amount, $currencyCode);
        }

        return (new Converter())->toBtc($amount, $currencyCode);
    }
}
