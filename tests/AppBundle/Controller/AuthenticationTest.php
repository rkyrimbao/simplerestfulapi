<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationTest extends WebTestCase
{
    public function testNTokenRequest()
    {
        $client = static::createClient();

        $client->request('POST', '/league/create', [], [], ['HTTP_X-api-request' => '']);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}