<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RandomTeamControllerTest extends WebTestCase
{
    public function testRandomTeam(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://localhost/api/v1/random/team');

        $this->assertEquals(6, count(json_decode($client->getResponse()->getContent())));
    }
}
