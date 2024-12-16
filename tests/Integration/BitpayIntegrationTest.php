<?php

namespace Test\Integration;

use GuzzleHttp\Client;
use Jimmerioles\BitcoinCurrencyConverter\Provider\BitpayProvider;
use Test\TestCase;

class BitpayIntegrationTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function test_api_returns_expected_json_structure()
    {
        $response = $this->client->request(
            'GET',
            BitpayProvider::getApiEndpoint(),
            ['headers' => ['Accept' => 'application/json']]
        );

        $body = $response->getBody();
        $responseArray = json_decode($body, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($body);

        foreach ($responseArray as $currency) {
            $this->assertArrayHasKey('code', $currency);
            $this->assertArrayHasKey('rate', $currency);
        }
    }
}
