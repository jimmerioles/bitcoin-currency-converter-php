<?php

use Jimmerioles\BitcoinCurrencyConverter\Converter;
use Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface;

if (! function_exists('to_currency')) {
    /**
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string                  $currencyCode
     * @param  float                   $btcAmount
     */
    function to_currency($currencyCode, $btcAmount, ?ProviderInterface $provider): float
    {
        if ($provider instanceof \Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface) {
            return (new Converter($provider))->toCurrency($currencyCode, $btcAmount);
        }

        return (new Converter())->toCurrency($currencyCode, $btcAmount);
    }
}

if (! function_exists('to_btc')) {
    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string $currencyCode
     */
    function to_btc($amount, $currencyCode, ?ProviderInterface $provider = null): float
    {
        if ($provider instanceof \Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface) {
            return (new Converter($provider))->toBtc($amount, $currencyCode);
        }

        return (new Converter())->toBtc($amount, $currencyCode);
    }
}
