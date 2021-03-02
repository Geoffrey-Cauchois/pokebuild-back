<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest extends WebTestCase
{
    public function testError404(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/dfgfsgh');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h2', 'Luxio');
    }
}
