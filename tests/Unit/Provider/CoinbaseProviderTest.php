<?php

namespace Test\Unit\Provider;

use Test\TestCase;
use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

class CoinbaseProviderTest extends TestCase
{
    public function test_getRate_gets_rate_of_currency()
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

    public function test_getRate_gets_rate_of_currency_passed_with_lowercaps_currency_code()
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

    public function test_getRate_throws_exception_when_passed_with_invalid_currency_code_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument passed not a valid currency code, 'FOO' given.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('FOO');
    }

    public function test_getRate_throws_exception_when_provider_does_not_support_currency_code()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$currencyCode 'DOGE' not supported by provider.");

        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $this->stubBody())]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('DOGE');
    }

    public function test_getRate_throws_exception_when_provider_recieves_unexpected_api_endpoint_data()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Not OK response received from API endpoint.");

        $mock = new MockHandler([new Response(206, ['Content-Type' => 'application/json'])]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $provider = new CoinbaseProvider($client);

        $provider->getRate('USD');
    }

    public function test_requests_exchange_rates_from_api_endpoint_only_once_with_same_instance()
    {
        $mockResponse = m::mock(Response::class)->makePartial();
        $mockResponse->shouldReceive('getBody')->once()->andReturn($this->stubBody());

        $mock = m::mock(Client::class);
        $mock->shouldReceive('request')->once()->andReturn($mockResponse);

        $provider = new CoinbaseProvider($mock);

        $provider->getRate('USD');
        $provider->getRate('USD');
    }

    public function test_uses_guzzleHttp_as_default_http_client()
    {
        $provider = new CoinbaseProvider;

        $this->assertAttributeInstanceOf(Client::class, 'client', $provider);
    }

    private function stubBody()
    {
        return '{"data":{"currency":"BTC","rates":{"AED":"16016.03","AFN":"298354.43","ALL":"480232.88","AMD":"2081161.76","ANG":"7744.46","AOA":"723512.93","ARS":"75138.95","AUD":"5475.81","AWG":"7833.81","AZN":"7413.02","BAM":"7128.01","BBD":"8721.20","BDT":"353913.82","BGN":"7077.42","BHD":"1644.840","BIF":"7574362","BMD":"4360.60","BND":"5896.97","BOB":"30344.32","BRL":"13813.51","BSD":"4360.60","BTC":"1.00000000","BTN":"278644.52","BWP":"44077.24","BYN":"8368.20","BYR":"87326466","BZD":"8769.12","CAD":"5428.31","CDF":"6710203.60","CHF":"4121.43","CLF":"101.9072","CLP":"2739765","CNY":"28763.83","COP":"12848725.93","CRC":"2511771.01","CUC":"4360.60","CVE":"402701.41","CZK":"94401.04","DJF":"780417","DKK":"26896.76","DOP":"204810.88","DZD":"478739.37","EEK":"63724.46","EGP":"77156.46","ERN":"66833.73","ETB":"101807.54","ETH":"12.14624074","EUR":"3645.16","FJD":"8765.25","FKP":"3363.68","GBP":"3390.59","GEL":"10523.87","GGP":"3363.68","GHS":"19272.32","GIP":"3363.68","GMD":"200914.64","GNF":"38655629","GTQ":"31669.39","GYD":"900696.72","HKD":"34118.90","HNL":"101739.43","HRK":"26792.33","HTG":"288442.81","HUF":"1102471","IDR":"58141377.38","ILS":"15583.60","IMP":"3363.68","INR":"279056.60","IQD":"5081189.150","ISK":"454012","JEP":"3363.68","JMD":"556266.11","JOD":"3091.670","JPY":"472848","KES":"450769.76","KGS":"299195.78","KHR":"17628815.65","KMF":"1795690","KRW":"4908073","KWD":"1313.701","KYD":"3625.59","KZT":"1453732.79","LAK":"36071319.26","LBP":"6560740.73","LKR":"665331.19","LRD":"501600.89","LSL":"56861.82","LTC":"70.62146893","LTL":"14062.26","LVL":"2861.69","LYD":"5935.841","MAD":"40541.37","MDL":"77639.48","MGA":"12932209.0","MKD":"226587.91","MMK":"5925183.28","MNT":"10598009.79","MOP":"35056.68","MRO":"1588461.1","MTL":"2981.51","MUR":"142495.69","MVR":"67372.29","MWK":"3154806.89","MXN":"77958.15","MYR":"18605.81","MZN":"266645.71","NAD":"56861.82","NGN":"1574896.10","NIO":"129967.08","NOK":"33652.10","NPR":"444809.81","NZD":"5991.39","OMR":"1678.918","PAB":"4360.60","PEN":"14123.15","PGK":"14047.74","PHP":"222496.34","PKR":"458515.38","PLN":"15428.46","PYG":"24569147","QAR":"16047.04","RON":"16642.90","RSD":"430326.90","RUB":"256107.63","RWF":"3608942","SAR":"16355.10","SBD":"33911.91","SCR":"59160.27","SEK":"34548.68","SGD":"5893.29","SHP":"3363.68","SLL":"32938455.24","SOS":"2516415.05","SRD":"32434.14","SSP":"547407.48","STD":"89259184.38","SVC":"38067.04","SZL":"56847.04","THB":"144597.50","TJS":"38348.73","TMT":"15305.62","TND":"10527.805","TOP":"9655.68","TRY":"15030.85","TTD":"29541.39","TWD":"131437.21","TZS":"9768834.15","UAH":"110956.55","UGX":"15683334","USD":"4360.60","UYU":"125455.72","UZS":"18194821.53","VEF":"44405.52","VND":"98933906","VUV":"458634","WST":"10871.73","XAF":"2371553","XAG":"248","XAU":"3","XCD":"11784.74","XDR":"3076","XOF":"2371553","XPD":"5","XPF":"431433","XPT":"4","YER":"1091378.13","ZAR":"56943.87","ZMK":"22906559.96","ZMW":"39414.98","ZWL":"1405661.26"}}}';
    }
}
