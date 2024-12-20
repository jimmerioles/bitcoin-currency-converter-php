<?php

declare(strict_types=1);

namespace Test\Feature;

use Test\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;

class ConvertUsingHelperWithCoindeskExchangeRatesTest extends TestCase
{
    public function test_can_convert_btc_to_fiat_currency_using_coindesk_exchange_rates(): void
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $this->assertEquals(1539.53, to_currency('USD', 0.33, $provider));
    }

    public function test_can_convert_fiat_currency_to_btc_using_coindesk_exchange_rates(): void
    {
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoindeskProvider($client);

        $this->assertEquals(0.10717565, to_btc(500, 'USD', $provider));
    }

    private function stubBody(): string
    {
        return '{"time":{"updated":"Aug 30, 2017 05:17:00 UTC","updatedISO":"2017-08-30T05:17:00+00:00","updateduk":"Aug 30, 2017 at 06:17 BST"},"disclaimer":"This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org","chartName":"Bitcoin","bpi":{"USD":{"code":"USD","symbol":"$","rate":"4,665.2388","description":"United States Dollar","rate_float":4665.2388},"GBP":{"code":"GBP","symbol":"£","rate":"3,607.9790","description":"British Pound Sterling","rate_float":3607.979},"EUR":{"code":"EUR","symbol":"€","rate":"3,896.2674","description":"Euro","rate_float":3896.2674}}}';
    }
}
