<?php

use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

if (! function_exists('format_to_currency')) {
    /**
     * Format to currency type.
     */
    function format_to_currency(string $currencyCode, float $value): float
    {
        if (is_crypto_currency($currencyCode)) {
            return round($value, 8, PHP_ROUND_HALF_UP);
        }

        if (is_fiat_currency($currencyCode)) {
            return round($value, 2, PHP_ROUND_HALF_UP);
        }

        throw new InvalidArgumentException('Argument $currencyCode not valid currency code, \'' . $currencyCode . '\' given.');
    }
}
