<?php

namespace Tests\AppBundle\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testWhenCredentialsNotProvided($url)
    {
        $client = static::createClient();

        $client->request(
            'GET',
            $url,
            [],
            [],
            ['HTTP_accept' => 'application/json']
        );

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Invalid credentials.', $response->getContent());
    }

    /**
     * @dataProvider provideUrls
     */
    public function testWhenWrongCredentialsProvided($url)
    {
        $client = static::createClient();

        $client->request(
            'GET',
            $url,
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'some_apikey']
        );

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('API Key "some_apikey" does not exist.', $response->getContent());
    }

    public function provideUrls()
    {
        return array(
            array('/api/authors'),
            array('/api/quotes'),
        );
    }
}
