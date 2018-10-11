<?php

namespace Tests\AppBundle\API;

use AppBundle\DataFixtures\LoadAuthor;
use AppBundle\Test\AbstractWebTestCase;

class AuthorsGetTest extends AbstractWebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(
            [
                LoadAuthor::class,
            ]
        );
    }

    public function testGet()
    {
        /*** testing get first site authors ***/
        $this->client->request(
            'GET',
            '/api/authors',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $authorsFirstSite = json_decode($content, true);
        $this->assertCount(2, $authorsFirstSite);
        $this->assertEquals('London', $authorsFirstSite[0]['lastname']);
        $this->assertEquals('Tolkien', $authorsFirstSite[1]['lastname']);

        /*** testing get second site authors ***/
        $this->client->request(
            'GET',
            '/api/authors',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $authorsSecondSite = json_decode($content, true);
        $this->assertCount(3, $authorsSecondSite);
        $this->assertEquals('Howard', $authorsSecondSite[0]['lastname']);
        $this->assertEquals('King', $authorsSecondSite[1]['lastname']);
        $this->assertEquals('Clarke', $authorsSecondSite[2]['lastname']);

        /*** testing get author from first site authors ***/
        $this->client->request(
            'GET',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $author = json_decode($content, true);
        $this->assertEquals('London', $author['lastname']);

        /*** testing get author from second site with first site api key returns 404 ***/
        $this->client->request(
            'GET',
            '/api/authors/'.$authorsSecondSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());


        /*** testing get author from second site authors ***/
        $this->client->request(
            'GET',
            '/api/authors/'.$authorsSecondSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $author = json_decode($content, true);
        $this->assertEquals('Howard', $author['lastname']);

        /*** testing get author from first site with second site api key returns 404 ***/
        $this->client->request(
            'GET',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}
