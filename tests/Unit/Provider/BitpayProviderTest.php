<?php

namespace Test\Unit\Provider;

use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Cache\Repository;
use GuzzleHttp\Handler\MockHandler;
use Psr\SimpleCache\CacheInterface;
use Jimmerioles\BitcoinCurrencyConverter\Provider\BitpayProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

class BitpayProviderTest extends ProviderTest
{
    public function test_getRate_gets_rate_of_currency()
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new BitpayProvider($client);

        $result = $provider->getRate('USD');
        $result2 = $provider->getRate('BTC');

        $this->assertEquals(4183.99, $result);
        $this->assertEquals(1, $result2);
    }

    public function test_getRate_gets_rate_of_currency_passed_with_lowercaps_currency_code()
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new BitpayProvider($client);

        $result = $provider->getRate('usd');
        $result2 = $provider->getRate('btc');

        $this->assertEquals(4183.99, $result);
        $this->assertEquals(1, $result2);
    }

    public function test_getRate_throws_exception_when_passed_with_invalid_currency_code_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument passed not a valid currency code, 'FOO' given.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new BitpayProvider($client);

        $provider->getRate('FOO');
    }

    public function test_getRate_throws_exception_when_provider_does_not_support_currency_code()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$currencyCode 'DOGE' not supported by provider.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new BitpayProvider($client);

        $provider->getRate('DOGE');
    }

    public function test_getRate_throws_exception_when_provider_recieves_unexpected_api_endpoint_data()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Not OK response received from API endpoint.");

        $mock = new MockHandler([new Response(206, ['Content-Type' => 'application/json'])]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new BitpayProvider($client);

        $provider->getRate('USD');
    }

    public function test_requests_exchange_rates_from_api_endpoint_only_once_with_same_instance()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());

        $mock = m::mock(Client::class);
        $mock->shouldReceive('request')->once()->andReturn($mockResponse);

        $provider = new BitpayProvider($mock);

        $provider->getRate('USD');
        $provider->getRate('USD');
    }

    public function test_uses_guzzleHttp_as_default_http_client()
    {
        $provider = new BitpayProvider;

        $this->assertAttributeInstanceOf(Client::class, 'client', $provider);
    }

    public function test_uses_illuminateCache_as_default_cache_implementation()
    {
        $provider = new BitpayProvider;

        $this->assertAttributeInstanceOf(Repository::class, 'cache', $provider);
    }

    public function test_caches_exchange_rates_on_first_api_call()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('bitpay-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('bitpay-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $provider = new BitpayProvider($mockClient, $mockCache);

        $result = $provider->getRate('USD');

        $this->assertEquals(4183.99, $result);
    }

    public function test_fetches_exchange_rates_in_cache_after_first_fetching_from_api_endpoint()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('bitpay-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('bitpay-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $mockResponse2 = m::mock(Response::class)->makePartial();
        $mockResponse2->shouldReceive('getBody')->never();
        $mockClient2 = m::mock(Client::class);
        $mockClient2->shouldReceive('request')->never();

        $mockCache2 = m::mock(CacheInterface::class);
        $mockCache2->shouldReceive('has')->once()->with('bitpay-cache-key')->andReturn(true)->ordered();
        $mockCache2->shouldReceive('get')->once()->with('bitpay-cache-key')->andReturn($this->ratesArrayStub())->ordered();


        $provider = new BitpayProvider($mockClient, $mockCache);
        $result = $provider->getRate('USD');
        $provider2 = new BitpayProvider($mockClient2, $mockCache2);
        $result2 = $provider2->getRate('USD');

        $this->assertEquals(4183.99, $result);
        $this->assertEquals(4183.99, $result2);
    }

    private function stubBody()
    {
        return $this->getStubResponse('tests/fixtures/bitpay-response.json');
    }

    private function ratesArrayStub()
    {
        $arrayData = json_decode($this->stubBody(), true);

        foreach ($arrayData as $data) {
            $exchangeRatesArray[$data['code']] = $data['rate'];
        }

        return $exchangeRatesArray;
    }
}
