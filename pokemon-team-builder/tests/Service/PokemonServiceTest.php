<?php

namespace App\Tests\Service;

use App\Entity\Pokemon;
use App\Repository\TypeRepository;
use PHPUnit\Framework\TestCase;
use App\Service\PokemonService;

class PokemonServiceTest extends TestCase
{
    public function testPokemonService(): void
    {
        $pokemonToTest = new Pokemon;
        $pokemonToTest->getId('721');
        $pokemonService = new PokemonService;
        
        $typeToTest = $pokemonToTest>getType('Roche');
        $this->assertTrue(true);
    }
}
