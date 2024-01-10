<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{

    public function testItAuthenticates()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/token',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'Marcelo',
                'password' => 'Sanchez',
            ])
        );
        $response = $client->getResponse();

        $this->assertEquals($response->getStatusCode(), '200');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsString($data['token']);
    }

    public function testItFailsToAuthenticate()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/token',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'Marcelo',
                'password' => 'NOTRIGHT',
            ])
        );

        $response = $client->getResponse();

        $this->assertEquals($response->getStatusCode(), '401');
    }
}
