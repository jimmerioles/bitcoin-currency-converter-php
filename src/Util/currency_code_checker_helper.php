<?php

use Jimmerioles\BitcoinCurrencyConverter\Util\CurrencyCodeChecker;

if (! function_exists('is_crypto_currency')) {
    /**
     * Check if cryptocurrency.
     */
    function is_crypto_currency(string $currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isCryptoCurrency($currencyCode);
    }
}

if (! function_exists('is_fiat_currency')) {
    /**
     * Check if fiat currency.
     */
    function is_fiat_currency(string $currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isFiatCurrency($currencyCode);
    }
}

if (! function_exists('is_currency_code')) {
    /**
     * Check if currency code.
     */
    function is_currency_code(string $currencyCode): bool
    {
        return (new CurrencyCodeChecker())->isCurrencyCode($currencyCode);
    }
}
