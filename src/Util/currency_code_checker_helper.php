<?php

use Jimmerioles\BitcoinCurrencyConverter\Util\CurrencyCodeChecker;

if (! function_exists('is_crypto_currency')) {
    /**
     * Check if cryptocurrency.
     *
     * @param  string  $currencyCode
     */
    function is_crypto_currency($currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isCryptoCurrency($currencyCode);
    }
}

if (! function_exists('is_fiat_currency')) {
    /**
     * Check if fiat currency.
     *
     * @param  string  $currencyCode
     */
    function is_fiat_currency($currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isFiatCurrency($currencyCode);
    }
}

if (! function_exists('is_currency_code')) {
    /**
     * Check if currency code.
     *
     * @param  string  $currencyCode
     */
    function is_currency_code($currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isCurrencyCode($currencyCode);
    }
}
