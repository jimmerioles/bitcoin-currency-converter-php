<?php

namespace Test\Unit\Provider;

use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Cache\Repository;
use GuzzleHttp\Handler\MockHandler;
use Psr\SimpleCache\CacheInterface;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

class CoindeskProviderTest extends ProviderTest
{
    public function test_getRate_gets_rate_of_currency()
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $result = $provider->getRate('USD');

        $this->assertEquals(4665.2388, $result);
    }

    public function test_getRate_gets_rate_of_currency_passed_with_lowercaps_currency_code()
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $result = $provider->getRate('usd');

        $this->assertEquals(4665.2388, $result);
    }

    public function test_getRate_throws_exception_when_passed_with_invalid_currency_code_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument passed not a valid currency code, 'FOO' given.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $provider->getRate('FOO');
    }

    public function test_getRate_throws_exception_when_provider_does_not_support_currency_code()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$currencyCode 'DOGE' not supported by provider.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $provider->getRate('DOGE');
    }

    public function test_getRate_throws_exception_when_provider_recieves_unexpected_api_endpoint_data()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Not OK response received from API endpoint.");

        $mock = new MockHandler([new Response(206, ['Content-Type' => 'application/json'])]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $provider->getRate('USD');
    }

    public function test_requests_exchange_rates_from_api_endpoint_only_once_with_same_instance()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());

        $mock = m::mock(Client::class);
        $mock->shouldReceive('request')->once()->andReturn($mockResponse);

        $provider = new CoindeskProvider($mock);

        $provider->getRate('USD');
        $provider->getRate('USD');
    }

    public function test_uses_guzzleHttp_as_default_http_client()
    {
        $provider = new CoindeskProvider;

        $this->assertAttributeInstanceOf(Client::class, 'client', $provider);
    }

    public function test_uses_illuminateCache_as_default_cache_implementation()
    {
        $provider = new CoindeskProvider;

        $this->assertAttributeInstanceOf(Repository::class, 'cache', $provider);
    }

    public function test_caches_exchange_rates_on_first_api_call()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('coindesk-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('coindesk-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $provider = new CoindeskProvider($mockClient, $mockCache);

        $result = $provider->getRate('USD');

        $this->assertEquals(4665.2388, $result);
    }

    public function test_fetches_exchange_rates_in_cache_after_first_fetching_from_api_endpoint()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('request')->once()->andReturn($mockResponse);

        $mockCache = m::mock(CacheInterface::class);
        $mockCache->shouldReceive('has')->once()->with('coindesk-cache-key')->andReturn(false)->ordered();
        $mockCache->shouldReceive('set')->once()->with('coindesk-cache-key', $this->ratesArrayStub(), 60)->andReturn(true)->ordered();

        $mockResponse2 = m::mock(Response::class)->makePartial();
        $mockResponse2->shouldReceive('getBody')->never();
        $mockClient2 = m::mock(Client::class);
        $mockClient2->shouldReceive('request')->never();

        $mockCache2 = m::mock(CacheInterface::class);
        $mockCache2->shouldReceive('has')->once()->with('coindesk-cache-key')->andReturn(true)->ordered();
        $mockCache2->shouldReceive('get')->once()->with('coindesk-cache-key')->andReturn($this->ratesArrayStub())->ordered();


        $provider = new CoindeskProvider($mockClient, $mockCache);
        $result = $provider->getRate('USD');
        $provider2 = new CoindeskProvider($mockClient2, $mockCache2);
        $result2 = $provider2->getRate('USD');

        $this->assertEquals(4665.2388, $result);
        $this->assertEquals(4665.2388, $result2);
    }

    private function stubBody()
    {
        return $this->getStubResponse('tests/fixtures/coindesk-response.json');
    }

    private function ratesArrayStub()
    {
        $arrayData = json_decode($this->stubBody(), true);

        foreach ($arrayData['bpi'] as $value) {
            $exchangeRatesArray[$value['code']] = $value['rate_float'];
        }

        return $exchangeRatesArray;
    }
}
