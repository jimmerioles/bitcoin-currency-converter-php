<?php

declare(strict_types=1);

namespace Test\Unit\Util;

use Test\TestCase;

class CurrencyCodeCheckerHelperTest extends TestCase
{
    public function test_can_check_if_crypto_currency(): void
    {
        $this->assertTrue(is_crypto_currency('BTC'));
        $this->assertTrue(is_crypto_currency('LTC'));
        $this->assertFalse(is_crypto_currency('USD'));
    }

    public function test_can_check_if_fiat_currency(): void
    {
        $this->assertTrue(is_fiat_currency('USD'));
        $this->assertTrue(is_fiat_currency('GBP'));
        $this->assertFalse(is_fiat_currency('BTC'));
    }

    public function test_can_check_if_currency_code(): void
    {
        $this->assertTrue(is_currency_code('USD'));
        $this->assertTrue(is_currency_code('LTC'));
        $this->assertFalse(is_currency_code('FOO'));
    }

    public function test_can_check_if_crypto_currency_in_lowercaps_currency_code(): void
    {
        $this->assertTrue(is_crypto_currency('btc'));
        $this->assertTrue(is_crypto_currency('ltc'));
        $this->assertFalse(is_crypto_currency('usd'));
    }

    public function test_can_check_if_fiat_currency_in_lowercaps_currency_code(): void
    {
        $this->assertTrue(is_fiat_currency('usd'));
        $this->assertTrue(is_fiat_currency('gbp'));
        $this->assertFalse(is_fiat_currency('btc'));
    }

    public function test_can_check_if_currency_code_in_lowercaps_currency_code(): void
    {
        $this->assertTrue(is_currency_code('usd'));
        $this->assertTrue(is_currency_code('ltc'));
        $this->assertFalse(is_currency_code('foo'));
    }
}
