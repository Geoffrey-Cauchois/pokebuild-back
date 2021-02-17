<?php

namespace App\Tests\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testPokemonList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon');

        $PokemonRepository = static::$container->get(PokemonRepository::class);
        $pokemonList = $PokemonRepository->findAll();
        $this->assertEquals($pokemonList, $client->getResponse()->getContent());
        
        
        
    }

    public function testPokemonId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/840');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"id":840', $client->getResponse()->getContent()); 
        
    }

    public function testPokemonName(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/Grillepattes');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Grillepattes', $client->getResponse()->getContent());
        
    }
}