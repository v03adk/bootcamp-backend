<?php

namespace Tests\AppBundle\API;

use AppBundle\DataFixtures\LoadAuthor;
use AppBundle\Test\AbstractWebTestCase;

class AuthorsUpdateTest extends AbstractWebTestCase
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

    public function testPost()
    {
        /*** get all first site authors ***/
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

        /*** send post without all mandatory fields ***/
        $this->client->request(
            'POST',
            '/api/authors',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['lastname' => 'Asimov'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $error = json_decode($content, true);
        $this->assertEquals('firstname', $error['violations'][0]['propertyPath']);
        $this->assertEquals('This value should not be blank.', $error['violations'][0]['message']);

        /*** get all first site authors again to check that count did not change ***/
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

        /*** send post with all mandatory fields ***/
        $this->client->request(
            'POST',
            '/api/authors',
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['lastname' => 'Asimov', 'firstname' => 'Isaac'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $author = json_decode($content, true);
        $this->assertArrayHasKey('id', $author);
        $this->assertEquals('Asimov', $author['lastname']);
        $this->assertEquals('Isaac', $author['firstname']);


        /*** get all first site authors again to check that count changed ***/
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
        $this->assertCount(3, $authorsFirstSite);
    }

    public function testPut()
    {
        /*** get all first site authors ***/
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

        /*** send put ***/
        $this->client->request(
            'PUT',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['lastname' => 'London edited'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $editedAuthor = json_decode($content, true);
        $this->assertEquals($authorsFirstSite[0]['id'], $editedAuthor['id']);
        $this->assertEquals('Jack', $editedAuthor['firstname']);
        $this->assertEquals('London edited', $editedAuthor['lastname']);

        /*** get all first site authors again to check that count did not change ***/
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

        /*** test that put for author from another site does not work ***/
        $this->client->request(
            'PUT',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['lastname' => 'London edited'])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testDelete()
    {
        /*** get all first site authors ***/
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

        /*** send delete ***/
        $this->client->request(
            'DELETE',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'first_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        /*** get all first site authors again to check that count changed ***/
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
        $this->assertCount(1, $authorsFirstSite);
        $this->assertEquals('Tolkien', $authorsFirstSite[0]['lastname']);

        /*** test that delete for author from another site does not work ***/
        $this->client->request(
            'DELETE',
            '/api/authors/'.$authorsFirstSite[0]['id'],
            [],
            [],
            ['HTTP_accept' => 'application/json', 'HTTP_apikey' => 'second_site_apikey']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}
