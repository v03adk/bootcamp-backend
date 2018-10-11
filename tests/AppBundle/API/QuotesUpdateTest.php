<?php

namespace Tests\AppBundle\API;

use AppBundle\DataFixtures\LoadQuote;
use AppBundle\Test\AbstractWebTestCase;

class QuotesUpdateTest extends AbstractWebTestCase
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

    public function testPost()
    {
        /*** get all second site quotes ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(6, $quotes);

        /*** send post without all mandatory fields ***/
        $this->client->request(
            'POST',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['quote' => 'Fiction is the truth inside the lie.'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $error = json_decode($content, true);
        $this->assertEquals('author', $error['violations'][0]['propertyPath']);
        $this->assertEquals('This value should not be blank.', $error['violations'][0]['message']);

        /*** get all second site quotes again to check that count did not change ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(6, $quotes);

        /*** send post with all mandatory fields ***/
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
        $this->assertEquals('King', $authorsSecondSite[1]['lastname']);

        $this->client->request(
            'POST',
            '/api/quotes',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['quote' => 'Fiction is the truth inside the lie.', 'author' => $authorsSecondSite[1]['id']])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertArrayHasKey('id', $quote);
        $this->assertEquals('Fiction is the truth inside the lie.', $quote['quote']);
        $author = $quote['author'];
        $this->assertEquals('King', $author['lastname']);
        $this->assertEquals('Steven', $author['firstname']);


        /*** get all second site quotes again to check that count changed ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(7, $quotes);
    }

    public function testPut()
    {
        /*** get all second site quotes ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(6, $quotes);

        /*** send put ***/
        $this->client->request(
            'PUT',
            '/api/quotes/'.$quotes[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['quote' => 'Fiction is the truth inside the lie.'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $quote = json_decode($content, true);
        $this->assertEquals($quotes[0]['id'], $quote['id']);
        $this->assertEquals('King', $quote['author']['lastname']);
        $this->assertEquals('Fiction is the truth inside the lie.', $quote['quote']);
        $this->assertNotEquals($quotes[0]['quote'], $quote['quote']);

        /*** get all second site quotes again to check that count did not change ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(6, $quotes);

        /*** test that put for quote from another site does not work ***/
        $this->client->request(
            'PUT',
            '/api/quotes/'.$quotes[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['quote' => 'The truth is out there'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testDelete()
    {
        /*** get all second site quotes ***/
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
        $quotes = json_decode($content, true);
        $this->assertCount(6, $quotes);

        /*** send delete ***/
        $this->client->request(
            'DELETE',
            '/api/quotes/'.$quotes[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        /*** get all second site quotes again to check that count changed ***/
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
        $newQuotes = json_decode($content, true);
        $this->assertCount(5, $newQuotes);
        $newQuotesIds = [];
        foreach ($newQuotes as $quote) {
            $newQuotesIds[] = $quote['id'];
        }
        $this->assertNotContains($quotes[0]['id'], $newQuotesIds);


        /*** test that delete for quote from another site does not work ***/
        $this->client->request(
            'DELETE',
            '/api/quotes/'.$quotes[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}
