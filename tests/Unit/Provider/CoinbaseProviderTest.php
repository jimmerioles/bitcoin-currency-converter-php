<?php

namespace Test\Unit\Provider;

use Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Cache\Repository;
use Psr\SimpleCache\CacheInterface;
use GuzzleHttp\Handler\MockHandler;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;

class CoinbaseProviderTest extends ProviderTest
{
    public function test_getRate_gets_rate_of_currency(): void
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $result = $provider->getRate('USD');
        $result2 = $provider->getRate('BTC');
        $result3 = $provider->getRate('LTC');

        $this->assertEquals(4360.60, $result);
        $this->assertEquals(1, $result2);
        $this->assertEquals(70.62146893, $result3);
    }

    public function test_getRate_gets_rate_of_currency_passed_with_lowercaps_currency_code(): void
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $result = $provider->getRate('usd');
        $result2 = $provider->getRate('btc');
        $result3 = $provider->getRate('ltc');

        $this->assertEquals(4360.60, $result);
        $this->assertEquals(1, $result2);
        $this->assertEquals(70.62146893, $result3);
    }

    public function test_getRate_throws_exception_when_passed_with_invalid_currency_code_argument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument passed not a valid currency code, 'FOO' given.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('FOO');
    }

    public function test_getRate_throws_exception_when_provider_does_not_support_currency_code(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$currencyCode 'DOGE' not supported by provider.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('DOGE');
    }

    public function test_getRate_throws_exception_when_provider_recieves_unexpected_api_endpoint_data(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Not OK response received from API endpoint.");

        $mock = new MockHandler([new Response(206, ['Content-Type' => 'application/json'])]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('USD');
    }

    public function test_requests_exchange_rates_from_api_endpoint_only_once_with_same_instance(): void
    {
        $mockStream = m::mock(StreamInterface::class);
        $mockStream->shouldReceive('__toString')->once()->andReturn($this->stubBody());

        $mockResponse = m::mock(ResponseInterface::class);
        $mockResponse->shouldReceive('getStatusCode')->once()->andReturn(200);
        $mockResponse->shouldReceive('getBody')->once()->andReturn($mockStream);

        $mock = m::mock(Client::class);
        $mock->shouldReceive('request')->once()->andReturn($mockResponse);

        $provider = new CoinbaseProvider($mock);

        $rates = json_decode($this->stubBody(), true)['data']['rates'];

        $this->assertEquals($rates['USD'], $provider->getRate('USD'));
        $this->assertEquals($rates['USD'], $provider->getRate('USD'));
    }

    public function test_uses_guzzleHttp_as_default_http_client(): void
    {
        $provider = new CoinbaseProvider();

        $reflection = new ReflectionClass($provider);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $client = $property->getValue($provider);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_uses_illuminateCache_as_default_cache_implementation(): void
    {
        $provider = new CoinbaseProvider();

        $reflection = new ReflectionClass($provider);
        $property = $reflection->getProperty('cache');
        $property->setAccessible(true);
        $cache = $property->getValue($provider);

        $this->assertInstanceOf(Repository::class, $cache);
    }

    public function test_caches_exchange_rates_on_first_api_call(): void
    {
        $mockStream = m::mock(StreamInterface::class);
        $mockStream->shouldReceive('__toString')->once()->andReturn($this->stubBody());

        $mockResponse = m::mock(ResponseInterface::class);
        $mockResponse->shouldReceive('getStatusCode')->once()->andReturn(200);
        $mockResponse->shouldReceive('getBody')->once()->andReturn($mockStream);

        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('coinbase-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('coinbase-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $provider = new CoinbaseProvider($mockClient, $mockCache);

        $result = $provider->getRate('USD');

        $this->assertEquals(4360.60, $result);
    }

    public function test_fetches_exchange_rates_in_cache_after_first_fetching_from_api_endpoint(): void
    {
        $mockStream = m::mock(StreamInterface::class);
        $mockStream->shouldReceive('__toString')->once()->andReturn($this->stubBody());
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($mockStream);
        $mockResponse->shouldReceive('getStatusCode')->once()->andReturn(200);
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('coinbase-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('coinbase-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $mockResponse2 = m::mock(Response::class)->makePartial();
        $mockResponse2->shouldReceive('getBody')->never();
        $mockResponse->shouldReceive('getStatusCode')->never();
        $mockClient2 = m::mock(Client::class);
        $mockClient2->shouldReceive('request')->never();

        $mockCache2 = m::mock(CacheInterface::class);
        $mockCache2->shouldReceive('has')->once()->with('coinbase-cache-key')->andReturn(true)->ordered();
        $mockCache2->shouldReceive('get')->once()->with('coinbase-cache-key')->andReturn($this->ratesArrayStub())->ordered();


        $provider = new CoinbaseProvider($mockClient, $mockCache);
        $result = $provider->getRate('USD');
        $provider2 = new CoinbaseProvider($mockClient2, $mockCache2);
        $result2 = $provider2->getRate('USD');

        $this->assertEquals(4360.60, $result);
        $this->assertEquals(4360.60, $result2);
    }

    private function stubBody()
    {
        return $this->getStubResponse('tests/fixtures/coinbase-response.json');
    }

    private function ratesArrayStub()
    {
        $array = json_decode($this->stubBody(), true);

        return $array['data']['rates'];
    }
}
