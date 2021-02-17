<?php

namespace App\Tests\Controller;

use App\Repository\PokemonRepository;
use App\Service\PokemonService;
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
        $this->assertEquals(898, count(json_decode($client->getResponse()->getContent())));
        
    }

    public function testPokemonId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/840');

        $PokemonRepository = static::$container->get(PokemonRepository::class);
        $pokemonToTest = $PokemonRepository->find(840);
        $pokemonToTestStat = $pokemonToTest->getAttack();
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"attack":40', $client->getResponse()->getContent()); 
        
    }

    public function testPokemonName(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/Grillepattes');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Grillepattes', $client->getResponse()->getContent());
        
    }

    public function testPokemonResistance(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/type/resistance/eau');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Carapuce', $client->getResponse()->getContent());
        
    }

    public function testPokemonDoubleWeakness(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/type/double-weakness/feu');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Cizayox', $client->getResponse()->getContent());
        
    }

    public function testPokemonImmunity(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET',
         'http://localhost/api/v1/pokemon/type/immunity/spectre');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Roucool', $client->getResponse()->getContent());
        
    }
}