<?php

namespace Test\Unit\Util;

use Test\TestCase;
use Jimmerioles\BitcoinCurrencyConverter\Util\CurrencyCodeChecker;

class CurrencyCodeCheckerTest extends TestCase
{
    public function test_can_check_if_currency_code()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isCurrencyCode('USD'));
        $this->assertTrue($check->isCurrencyCode('GBP'));
        $this->assertFalse($check->isCurrencyCode('FOO'));
    }

    public function test_can_check_if_currency_code_in_lowerercaps()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isCurrencyCode('usd'));
        $this->assertTrue($check->isCurrencyCode('gbp'));
        $this->assertFalse($check->isCurrencyCode('foo'));
    }

    public function test_can_check_currency_code_if_fiat_currency()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isFiatCurrency('USD'));
        $this->assertTrue($check->isFiatCurrency('GBP'));
        $this->assertFalse($check->isFiatCurrency('BTC'));
    }

    public function test_can_check_currency_code_if_crypto_currency()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isCryptoCurrency('BTC'));
        $this->assertTrue($check->isCryptoCurrency('LTC'));
        $this->assertFalse($check->isCryptoCurrency('USD'));
    }

    public function test_can_check_currency_code_in_lowercaps_if_fiat_currency()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isFiatCurrency('usd'));
        $this->assertTrue($check->isFiatCurrency('gbp'));
        $this->assertFalse($check->isFiatCurrency('btc'));
    }

    public function test_can_check_currency_code_in_lowercaps_if_crypto_currency()
    {
        $check = new CurrencyCodeChecker();

        $this->assertTrue($check->isCryptoCurrency('btc'));
        $this->assertTrue($check->isCryptoCurrency('ltc'));
        $this->assertFalse($check->isCryptoCurrency('usd'));
    }
}
