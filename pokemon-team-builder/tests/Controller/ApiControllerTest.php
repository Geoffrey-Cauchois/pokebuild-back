<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testPokemonList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon');

        $this->assertResponseIsSuccessful();
        
    }
}
