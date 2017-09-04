<?php

namespace Test\Unit\Provider;

use Test\TestCase;
use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

class CoindeskProviderTest extends TestCase
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
        $this->expectExceptionMessage("Argument \$currencyCode not valid currency code, 'FOO' given.");

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

    private function stubBody()
    {
        return '{"time":{"updated":"Aug 30, 2017 05:17:00 UTC","updatedISO":"2017-08-30T05:17:00+00:00","updateduk":"Aug 30, 2017 at 06:17 BST"},"disclaimer":"This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org","chartName":"Bitcoin","bpi":{"USD":{"code":"USD","symbol":"$","rate":"4,665.2388","description":"United States Dollar","rate_float":4665.2388},"GBP":{"code":"GBP","symbol":"£","rate":"3,607.9790","description":"British Pound Sterling","rate_float":3607.979},"EUR":{"code":"EUR","symbol":"€","rate":"3,896.2674","description":"Euro","rate_float":3896.2674}}}';
    }
}
