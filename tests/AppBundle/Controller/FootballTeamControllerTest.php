<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FootballTeamControllerTest extends WebTestCase
{
    public function testCreateWithNoName()
    {

        $client = static::createClient();

        $client->request('POST', '/team/create', [], [], ['HTTP_X-api-request' => '92d0585a-48ee-41be-8f4a-56e32a4e839b']);

        $response = json_decode($client->getResponse()->getContent(), true);

        

        $this->assertEquals(false, $response['success']);
        $this->assertEquals('`name` is required', $response['errors']['name']);
    }

    public function testCreateWithNoStrip()
    {

        $client = static::createClient();

        $client->request('POST', '/team/create', [
            'name' => 'TEST'
        ], [], ['HTTP_X-api-request' => '92d0585a-48ee-41be-8f4a-56e32a4e839b']);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(false, $response['success']);
        $this->assertEquals('`strip` is required', $response['errors']['strip']);
    }

    public function testCreateWithNoLeague()
    {

        $client = static::createClient();

        $client->request('POST', '/team/create', [
            'name' => 'TEST',
            'strip' => 'STRIP'
        ], [], ['HTTP_X-api-request' => '92d0585a-48ee-41be-8f4a-56e32a4e839b']);

        $response = json_decode($client->getResponse()->getContent(), true);

        

        $this->assertEquals(false, $response['success']);
        $this->assertEquals('`league` is required', $response['errors']['league']);
    }
}