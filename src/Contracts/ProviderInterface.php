<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Contracts;

interface ProviderInterface
{
    /**
     * Get rate of currency code.
     *
     * @param  string $currencyCode
     * @return float
     */
    public function getRate($currencyCode);
}
