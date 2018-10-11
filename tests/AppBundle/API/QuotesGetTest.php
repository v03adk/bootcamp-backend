<?php

namespace Tests\AppBundle\API;

use AppBundle\DataFixtures\LoadQuote;
use AppBundle\Test\AbstractWebTestCase;

class QuotesGetTest extends AbstractWebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(
            [
                LoadQuote::class,
            ]
        );
    }

    public function testGet()
    {
        /*** testing get first site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quotesFirstSite = json_decode($content, true);
        $this->assertCount(5, $quotesFirstSite);

        /*** testing get second site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quotesSecondSite = json_decode($content, true);
        $this->assertCount(6, $quotesSecondSite);

        /*** testing get quote from first site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes/'.$quotesFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertEquals('London', $quote['author']['lastname']);
        $this->assertEquals('Martin Eden is not about me', $quote['quote']);

        /*** testing get quote from second site with first site api key returns 404 ***/
        $this->client->request(
            'GET',
            '/api/quotes/'.$quotesSecondSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());


        /*** testing get quotes from second site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes/'.$quotesSecondSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertEquals('King', $quote['author']['lastname']);
        $this->assertEquals('I am the king of a horror', $quote['quote']);

        /*** testing get quote from first site with second site api key returns 404 ***/
        $this->client->request(
            'GET',
            '/api/quotes/'.$quotesFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testGetRandomQuote()
    {
        /*** get first site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quotesFirstSite = json_decode($content, true);
        $quotesFirstSiteIds = [];
        foreach ($quotesFirstSite as $quote) {
            $quotesFirstSiteIds[] = $quote['id'];
        }
        $this->assertCount(5, $quotesFirstSite);

        /*** get first site random quote ***/
        $this->client->request(
            'GET',
            '/api/quotes/random',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertContains($quote['id'], $quotesFirstSiteIds);

        /*** get second site quotes ***/
        $this->client->request(
            'GET',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quotesSecondSite = json_decode($content, true);
        $quotesSecondSiteIds = [];
        foreach ($quotesSecondSite as $quote) {
            $quotesSecondSiteIds[] = $quote['id'];
        }
        $this->assertCount(6, $quotesSecondSite);


        /*** get second site random quote ***/
        $this->client->request(
            'GET',
            '/api/quotes/random',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertContains($quote['id'], $quotesSecondSiteIds);
    }
}
