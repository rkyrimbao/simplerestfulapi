<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FootballLeagueControllerTest extends WebTestCase
{
    public function testCreateWithNoName()
    {

        $client = static::createClient();

        $client->request('POST', '/league/create', [], [], ['HTTP_X-api-request' => '92d0585a-48ee-41be-8f4a-56e32a4e839b']);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Please provide data', $response['errors']['name']);
    }
}