<?php

namespace Test\Integration;

use GuzzleHttp\Client;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;
use Test\TestCase;

class CoindeskIntegrationTest extends TestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = new Client();
    }

    public function test_api_returns_expected_json_structure()
    {
        $response = $this->client->request(
            'GET', 
            CoindeskProvider::getApiEndpoint(), 
            ['headers' => ['Accept' => 'application/json']]
        );

        $body = $response->getBody();
        $responseArray = json_decode($body, true);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($body);
        
        $this->assertArrayHasKey('bpi', $responseArray);

        foreach ($responseArray['bpi'] as $currency) {
            $this->assertArrayHasKey('code', $currency);
            $this->assertArrayHasKey('rate_float', $currency);
        }
    }
}