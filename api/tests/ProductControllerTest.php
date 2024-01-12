<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{

    protected function createAuthenticatedClient($username = 'Marcelo', $password = 'Sanchez')
    {
        $client = static::createClient();
        $client->request(
        'POST',
        '/token',
        [],
        [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode([
            'username' => $username,
            'password' => $password,
        ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testItFetchesAll(): void
    {
        $client = $this->createAuthenticatedClient('Marcelo', 'Sanchez');
        $client->request('GET', '/product/all');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

        $json = json_decode($response->getContent(), true);
        
        $this->assertEquals($json[0]['sku'], 'SKU_123');
        $this->assertEquals($json[1]['sku'], 'SKU_1234');
        $this->assertEquals($json[2]['sku'], 'SKU_12345');
    }

    public function testItFetchesUserProducts(): void
    {
        $client = $this->createAuthenticatedClient('Marcelo', 'Sanchez');
        $client->request('GET', '/product');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

        $json = json_decode($response->getContent(), true);
        
        $this->assertEquals($json[0]['sku'], 'SKU_123');
        $this->assertEquals($json[1]['sku'], 'SKU_1234');
    }

    public function testItGetsAProduct(): void
    {
        $client = $this->createAuthenticatedClient('Marcelo', 'Sanchez');
        $client->request('GET', '/product/1');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

        $json = json_decode($response->getContent(), true);
        
        $this->assertEquals($json['sku'], 'SKU_123');
    }

    public function testItUpdatesAProduct(): void
    {
        $client = $this->createAuthenticatedClient('Marcelo', 'Sanchez');
        $client->request(
            'POST',
            '/product/1',
            [],
            [],
            [],
            '{ "product_name": "First modified Product", "description": "My very first product. Look at it! Isn\'t it cute? For now" }'
        );
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

        $json = json_decode($response->getContent(), true);
        
        $this->assertEquals($json['productName'], 'First modified Product');
        $this->assertEquals($json['description'], 'My very first product. Look at it! Isn\'t it cute? For now');
    }
}
