<?php

namespace App\Tests\Service;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\PokemonService;

class PokemonServiceTest extends KernelTestCase
{
    private $pokemonService;
    private $pokemonRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->pokemonService = self::$container->get(PokemonService::class);
        $this->pokemonRepository = self::$container->get(PokemonRepository::class);


    }

    public function testPokemonService() 
    {
        $pokemonToTest = $this->pokemonRepository->find(850);
        $this->pokemonService->calculateResistances($pokemonToTest);
        $this->assertEquals($pokemonToTest->getResistances()['Roche']['damage_multiplier'], 4);

        
    }
}
