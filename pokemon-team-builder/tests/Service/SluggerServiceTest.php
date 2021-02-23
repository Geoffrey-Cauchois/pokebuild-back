<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SluggerServiceTest extends KernelTestCase
{
    private $sluggerService;
    private $pokemonRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->sluggerService = self::$container->get(Slugger::class);
        $this->pokemonRepository = self::$container->get(PokemonRepository::class);

    }

    public function testSlugger() 
    {
        $pokemonToTest = $this->pokemonRepository->find(35);
        $this->sluggerService->sluggifyPokemon($pokemonToTest);
        $this->assertEquals($pokemonToTest->getSlug(), 'Melofee');
  
    }
}
