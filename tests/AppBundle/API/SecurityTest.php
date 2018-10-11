<?php

namespace Tests\AppBundle\API;

use AppBundle\DataFixtures\LoadSite;
use AppBundle\Test\AbstractWebTestCase;

/**
 * Class SecurityTest
 */
class SecurityTest extends AbstractWebTestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures([
            LoadSite::class,
        ]);
    }

    /**
     * @dataProvider provideUrls
     */
    public function testWhenCredentialsNotProvided($url)
    {
        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['HTTP_accept' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Invalid credentials.', $response->getContent());
    }

    /**
     * @dataProvider provideUrls
     */
    public function testWhenWrongCredentialsProvided($url)
    {
        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'some_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('API Key "some_apikey" does not exist.', $response->getContent());
    }

    /**
     * @dataProvider provideUrls
     */
    public function testWhenCorrectCredentialsProvided($url)
    {
        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function provideUrls()
    {
        return array(
            array('/api/authors'),
            array('/api/quotes'),
            array('/api/quotes/random'),
        );
    }
}
