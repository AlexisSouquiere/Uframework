<?php

class ApiTest extends \TestCase
{
    private $con;
    private $client;
    private $endpoint;

    public function setUp()
    {
        $this->client   = new Goutte\Client();
        $this->endpoint = 'http://localhost:81';
    }

    public function testGetLocationsHtml()
    {
        $this->client->setHeader('Accept', 'text/html');
        $crawler  = $this->client->request('GET', sprintf('%s/locations', $this->endpoint));
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }
    public function testGetLocationsJson()
    {
        $this->client->setHeader('Accept', 'application/json');
        $crawler  = $this->client->request('GET', sprintf('%s/locations', $this->endpoint));
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
    }

    public function testPostLocationJson()
    {
        $this->client->setHeader('Accept', 'application/json');
        $this->client->setHeader('Content-Type', 'application/json');
        $this->client->request('POST', sprintf('%s/locations', $this->endpoint), [], [], [], json_encode(['name' => 'BBox', 'address' => 'Clermont-Ferrand', '_method' => 'POST']));
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatus());
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
    }
}
