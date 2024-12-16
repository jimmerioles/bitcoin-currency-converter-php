<?php

namespace Test\Unit\Util;

use Mockery as m;
use Test\TestCase;
use Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;

class ConverterHelperTest extends TestCase
{
    public function test_to_currency_converts_btc_to_fiat_currency()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1000);

        $this->assertEquals(500, to_currency('USD', 0.5, $mock));
    }

    public function test_to_currency_converts_btc_to_another_crypto_currency()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.5);

        $this->assertEquals(5.25, to_currency('LTC', 0.5, $mock));
    }

    public function test_to_currency_btc_to_fiat_currency_returns_fiat_currency_formatted_value()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(3525.66);

        $this->assertEquals(1163.47, to_currency('USD', 0.33, $mock));
    }

    public function test_to_currency_btc_to_another_crypt_currency_returns_crypto_currency_formatted_value()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.12345678);

        $this->assertEquals(3.34074074, to_currency('LTC', 0.33, $mock));
    }

    public function test_to_Currency_throws_exception_when_passed_with_non_numeric_btcAmount_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$btcAmount should be numeric, 'foooo' given.");

        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1);

        to_currency('USD', 'foooo', $mock);
    }

    public function test_to_btc_converts_fiat_currency_to_btc()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1000);

        $this->assertEquals(0.5, to_btc(500, 'USD', $mock));
    }

    public function test_to_btc_converts_another_crypto_currency_to_btc()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.5);

        $this->assertEquals(2, to_btc(21, 'LTC', $mock));
    }

    public function test_to_btc_returns_crypto_currency_formatted_value()
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(3525.66);

        $this->assertEquals(0.14181742, to_btc(500, 'USD', $mock));
    }

    public function test_to_btc_throws_exception_when_passed_with_non_numeric_amount_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$amount should be numeric, 'foooo' given.");

        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1);

        to_btc('foooo', 'USD', $mock);
    }
}
