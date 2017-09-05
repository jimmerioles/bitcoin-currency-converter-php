<?php

namespace Test\Unit\Provider;

use Test\TestCase;
use \Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Jimmerioles\BitcoinCurrencyConverter\Provider\BitpayProvider;
use Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Exception\UnexpectedValueException;

class BitpayProviderTest extends TestCase
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

    private function stubBody()
    {
        return '[{"code":"BTC","name":"Bitcoin","rate":1},{"code":"USD","name":"US Dollar","rate":4183.99},{"code":"EUR","name":"Eurozone Euro","rate":3508.832086},{"code":"GBP","name":"Pound Sterling","rate":3247.186271},{"code":"JPY","name":"Japanese Yen","rate":456807.964813},{"code":"CAD","name":"Canadian Dollar","rate":5222.372638},{"code":"AUD","name":"Australian Dollar","rate":5268.609912},{"code":"CNY","name":"Chinese Yuan","rate":27813.567236},{"code":"CHF","name":"Swiss Franc","rate":4000.89023},{"code":"SEK","name":"Swedish Krona","rate":33332.685181},{"code":"NZD","name":"New Zealand Dollar","rate":5776.759681},{"code":"KRW","name":"South Korean Won","rate":4686277.9995},{"code":"AED","name":"UAE Dirham","rate":15368.201117},{"code":"AFN","name":"Afghan Afghani","rate":283710.085915},{"code":"ALL","name":"Albanian Lek","rate":463981.771934},{"code":"AMD","name":"Armenian Dram","rate":1980285.533865},{"code":"ANG","name":"Netherlands Antillean Guilder","rate":7363.671776},{"code":"AOA","name":"Angolan Kwanza","rate":694209.712795},{"code":"ARS","name":"Argentine Peso","rate":75409.306967},{"code":"AWG","name":"Aruban Florin","rate":7516.533851},{"code":"AZN","name":"Azerbaijani Manat","rate":7112.783},{"code":"BAM","name":"Bosnia-Herzegovina Convertible Mark","rate":6846.944827},{"code":"BBD","name":"Barbadian Dollar","rate":8367.98},{"code":"BDT","name":"Bangladeshi Taka","rate":335902.595548},{"code":"BGN","name":"Bulgarian Lev","rate":6864.316754},{"code":"BHD","name":"Bahraini Dinar","rate":1574.686476},{"code":"BIF","name":"Burundian Franc","rate":7208388.447617},{"code":"BMD","name":"Bermudan Dollar","rate":4183.99},{"code":"BND","name":"Brunei Dollar","rate":5623.872503},{"code":"BOB","name":"Bolivian Boliviano","rate":28854.64018},{"code":"BRL","name":"Brazilian Real","rate":13204.605496},{"code":"BSD","name":"Bahamian Dollar","rate":4183.99},{"code":"BTN","name":"Bhutanese Ngultrum","rate":267685.408399},{"code":"BWP","name":"Botswanan Pula","rate":42150.465026},{"code":"BZD","name":"Belize Dollar","rate":8339.085365},{"code":"CDF","name":"Congolese Franc","rate":6438431.583214},{"code":"CLF","name":"Chilean Unit of Account (UF)","rate":98.784004},{"code":"CLP","name":"Chilean Peso","rate":2655160.054},{"code":"COP","name":"Colombian Peso","rate":12243902.8163},{"code":"CRC","name":"Costa Rican ColÃ³n","rate":2387358.092192},{"code":"CUP","name":"Cuban Peso","rate":106691.745},{"code":"CVE","name":"Cape Verdean Escudo","rate":388274.272},{"code":"CZK","name":"Czech Koruna","rate":91567.980947},{"code":"DJF","name":"Djiboutian Franc","rate":748808.6903},{"code":"DKK","name":"Danish Krone","rate":26105.742014},{"code":"DOP","name":"Dominican Peso","rate":194827.774677},{"code":"DZD","name":"Algerian Dinar","rate":458092.51313},{"code":"EGP","name":"Egyptian Pound","rate":74062.898985},{"code":"ETB","name":"Ethiopian Birr","rate":96770.701384},{"code":"FJD","name":"Fijian Dollar","rate":8521.515697},{"code":"FKP","name":"Falkland Islands Pound","rate":3247.186271},{"code":"GEL","name":"Georgian Lari","rate":10091.88848},{"code":"GHS","name":"Ghanaian Cedi","rate":18378.213731},{"code":"GIP","name":"Gibraltar Pound","rate":3247.186271},{"code":"GMD","name":"Gambian Dalasi","rate":192881.939},{"code":"GNF","name":"Guinean Franc","rate":36765138.529},{"code":"GTQ","name":"Guatemalan Quetzal","rate":30103.234843},{"code":"GYD","name":"Guyanaese Dollar","rate":860575.008491},{"code":"HKD","name":"Hong Kong Dollar","rate":32731.659201},{"code":"HNL","name":"Honduran Lempira","rate":96770.270433},{"code":"HRK","name":"Croatian Kuna","rate":26015.213022},{"code":"HTG","name":"Haitian Gourde","rate":271064.411275},{"code":"HUF","name":"Hungarian Forint","rate":1067513.668575},{"code":"IDR","name":"Indonesian Rupiah","rate":55641916.554334},{"code":"ILS","name":"Israeli Shekel","rate":14991.25709},{"code":"INR","name":"Indian Rupee","rate":267336.04105},{"code":"IQD","name":"Iraqi Dinar","rate":4819816.412567},{"code":"IRR","name":"Iranian Rial","rate":137554947.235},{"code":"ISK","name":"Icelandic KrÃ³na","rate":439733.03949},{"code":"JEP","name":"Jersey Pound","rate":3247.186271},{"code":"JMD","name":"Jamaican Dollar","rate":529348.971535},{"code":"JOD","name":"Jordanian Dinar","rate":2966.453094},{"code":"KES","name":"Kenyan Shilling","rate":430741.385573},{"code":"KGS","name":"Kyrgystani Som","rate":286380.513349},{"code":"KHR","name":"Cambodian Riel","rate":16565937.131373},{"code":"KMF","name":"Comorian Franc","rate":1732579.598193},{"code":"KPW","name":"North Korean Won","rate":3765591},{"code":"KWD","name":"Kuwaiti Dinar","rate":1263.146581},{"code":"KYD","name":"Cayman Islands Dollar","rate":3447.591024},{"code":"KZT","name":"Kazakhstani Tenge","rate":1379151.155542},{"code":"LAK","name":"Laotian Kip","rate":33911281.861001},{"code":"LBP","name":"Lebanese Pound","rate":6220052.245979},{"code":"LKR","name":"Sri Lankan Rupee","rate":633088.878118},{"code":"LRD","name":"Liberian Dollar","rate":480750.028153},{"code":"LSL","name":"Lesotho Loti","rate":54398.535096},{"code":"LYD","name":"Libyan Dinar","rate":5667.971757},{"code":"MAD","name":"Moroccan Dirham","rate":39020.263115},{"code":"MDL","name":"Moldovan Leu","rate":74031.51906},{"code":"MGA","name":"Malagasy Ariary","rate":12182543.90004},{"code":"MKD","name":"Macedonian Denar","rate":216855.695437},{"code":"MMK","name":"Myanma Kyat","rate":5655073.244034},{"code":"MNT","name":"Mongolian Tugrik","rate":10167095.7},{"code":"MOP","name":"Macanese Pataca","rate":33334.597264},{"code":"MRO","name":"Mauritanian Ouguiya","rate":1509050.44289},{"code":"MUR","name":"Mauritian Rupee","rate":138616.739297},{"code":"MVR","name":"Maldivian Rufiyaa","rate":64643.62037},{"code":"MWK","name":"Malawian Kwacha","rate":2997776.719221},{"code":"MXN","name":"Mexican Peso","rate":73819.842638},{"code":"MYR","name":"Malaysian Ringgit","rate":17846.746585},{"code":"MZN","name":"Mozambican Metical","rate":255854.574179},{"code":"NAD","name":"Namibian Dollar","rate":54398.535096},{"code":"NGN","name":"Nigerian Naira","rate":1488496.043913},{"code":"NIO","name":"Nicaraguan CÃ³rdoba","rate":123669.903629},{"code":"NOK","name":"Norwegian Krone","rate":32421.089992},{"code":"NPR","name":"Nepalese Rupee","rate":423936.535869},{"code":"OMR","name":"Omani Rial","rate":1609.187658},{"code":"PAB","name":"Panamanian Balboa","rate":4183.99},{"code":"PEN","name":"Peruvian Nuevo Sol","rate":13393.462437},{"code":"PGK","name":"Papua New Guinean Kina","rate":13163.82833},{"code":"PHP","name":"Philippine Peso","rate":213529.92965},{"code":"PKR","name":"Pakistani Rupee","rate":435915.161167},{"code":"PLN","name":"Polish Zloty","rate":14918.953559},{"code":"PYG","name":"Paraguayan Guarani","rate":23330137.4395},{"code":"QAR","name":"Qatari Rial","rate":15058.598409},{"code":"RON","name":"Romanian Leu","rate":16135.490491},{"code":"RSD","name":"Serbian Dinar","rate":417384.378241},{"code":"RUB","name":"Russian Ruble","rate":245185.99799},{"code":"RWF","name":"Rwandan Franc","rate":3430845.595671},{"code":"SAR","name":"Saudi Riyal","rate":15691.217697},{"code":"SBD","name":"Solomon Islands Dollar","rate":32677.99953},{"code":"SCR","name":"Seychellois Rupee","rate":56205.198714},{"code":"SDG","name":"Sudanese Pound","rate":27621.647615},{"code":"SGD","name":"Singapore Dollar","rate":5674.745637},{"code":"SHP","name":"Saint Helena Pound","rate":3247.186271},{"code":"SLL","name":"Sierra Leonean Leone","rate":31547284.6},{"code":"SOS","name":"Somali Shilling","rate":2392802.98197},{"code":"SRD","name":"Surinamese Dollar","rate":31120.51762},{"code":"STD","name":"SÃ£o TomÃ© and PrÃ­ncipe Dobra","rate":85981411.267244},{"code":"SVC","name":"Salvadoran ColÃ³n","rate":36198.659755},{"code":"SYP","name":"Syrian Pound","rate":2154712.96826},{"code":"SZL","name":"Swazi Lilangeni","rate":54416.045094},{"code":"THB","name":"Thai Baht","rate":139033.9877},{"code":"TJS","name":"Tajikistani Somoni","rate":36466.941378},{"code":"TMT","name":"Turkmenistani Manat","rate":14685.72122},{"code":"TND","name":"Tunisian Dinar","rate":10156.217326},{"code":"TOP","name":"Tongan PaÊ»anga","rate":9361.050027},{"code":"TRY","name":"Turkish Lira","rate":14410.013015},{"code":"TTD","name":"Trinidad and Tobago Dollar","rate":28057.293021},{"code":"TWD","name":"New Taiwan Dollar","rate":125963.20294},{"code":"TZS","name":"Tanzanian Shilling","rate":9282537.784685},{"code":"UAH","name":"Ukrainian Hryvnia","rate":105720.511217},{"code":"UGX","name":"Ugandan Shilling","rate":14905255.1755},{"code":"UYU","name":"Uruguayan Peso","rate":119589.601269},{"code":"UZS","name":"Uzbekistan Som","rate":17301844.6475},{"code":"VEF","name":"Venezuelan BolÃ­var Fuerte","rate":41777.232198},{"code":"VND","name":"Vietnamese Dong","rate":94829157.087061},{"code":"VUV","name":"Vanuatu Vatu","rate":436717.863833},{"code":"WST","name":"Samoan Tala","rate":10482.69825},{"code":"XAF","name":"CFA Franc BEAC","rate":2301643.634591},{"code":"XAG","name":"Silver (troy ounce)","rate":244.434804},{"code":"XAU","name":"Gold (troy ounce)","rate":3.232593},{"code":"XCD","name":"East Caribbean Dollar","rate":11307.442174},{"code":"XOF","name":"CFA Franc BCEAO","rate":2301643.634591},{"code":"XPF","name":"CFP Franc","rate":418715.167388},{"code":"YER","name":"Yemeni Rial","rate":1047332.786937},{"code":"ZAR","name":"South African Rand","rate":54614.600524},{"code":"ZMW","name":"Zambian Kwacha","rate":37439.790181},{"code":"ZWL","name":"Zimbabwean Dollar","rate":1348730.142474}]';
    }
}
