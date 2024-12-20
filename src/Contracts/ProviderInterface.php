<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Contracts;

interface ProviderInterface
{
    /**
     * Get rate of currency code.
     */
    public function getRate(string $currencyCode): float;
}
