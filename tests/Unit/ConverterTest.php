<?php

namespace Test\Unit;

use Mockery as m;
use Test\TestCase;
use Jimmerioles\BitcoinCurrencyConverter\Converter;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\ProviderInterface;
use ReflectionClass;

class ConverterTest extends TestCase
{
    public function test_toCurrency_converts_btc_to_fiat_currency(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1000);
        $convert = new Converter($mock);

        $result = $convert->toCurrency('USD', 0.5);

        $this->assertEquals(500, $result);
    }

    public function test_toCurrency_converts_btc_to_another_crypto_currency(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.5);
        $convert = new Converter($mock);

        $result = $convert->toCurrency('LTC', 0.5);

        $this->assertEquals(5.25, $result);
    }

    public function test_toCurrency_btc_to_fiat_currency_returns_fiat_currency_formatted_value(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(3525.66);
        $convert = new Converter($mock);

        $result = $convert->toCurrency('USD', 0.33);

        $this->assertEquals(1163.47, $result);
    }

    public function test_toCurrency_btc_to_another_crypt_currency_returns_crypto_currency_formatted_value(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.12345678);
        $convert = new Converter($mock);

        $result = $convert->toCurrency('LTC', 0.33);

        $this->assertEquals(3.34074074, $result);
    }

    public function test_toBtc_converts_fiat_currency_to_btc(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(1000);
        $convert = new Converter($mock);

        $result = $convert->toBtc(500, 'USD');

        $this->assertEquals(0.5, $result);
    }

    public function test_toBtc_converts_another_crypto_currency_to_btc(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('LTC')->andReturn(10.5);
        $convert = new Converter($mock);

        $result = $convert->toBtc(21, 'LTC');

        $this->assertEquals(2, $result);
    }

    public function test_toBtc_returns_crypto_currency_formatted_value(): void
    {
        $mock = m::mock(ProviderInterface::class);
        $mock->shouldReceive('getRate')->once()->with('USD')->andReturn(3525.66);
        $convert = new Converter($mock);

        $result = $convert->toBtc(500, 'USD');

        $this->assertEquals(0.14181742, $result);
    }

    public function test_uses_coinbase_as_default_exchange_rates_provider(): void
    {
        $convert = new Converter();

        $reflection = new ReflectionClass($convert);
        $property = $reflection->getProperty('provider');
        $property->setAccessible(true);
        $provider = $property->getValue($convert);

        $this->assertInstanceOf(CoinbaseProvider::class, $provider);
    }
}
