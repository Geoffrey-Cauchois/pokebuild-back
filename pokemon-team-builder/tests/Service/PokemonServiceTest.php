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

    public function testDamageMultiplier() 
    {
        $pokemonToTest = $this->pokemonRepository->find(850);
        $this->pokemonService->calculateResistances($pokemonToTest);
        $this->assertEquals($pokemonToTest->getResistances()['Roche']['damage_multiplier'], 4);
  
    }

    public function testDamageRelation() 
    {
        $pokemonToTest = $this->pokemonRepository->find(840);
        $this->pokemonService->calculateResistances($pokemonToTest);
        $this->assertEquals($pokemonToTest->getResistances()['Vol']['damage_relation'], 'vulnerable');
  
    }

    public function testCoverage()
    {

      $pokemonToTestIds = [455, 149, 12];

      $testedTeam = $this->pokemonService->calculateDefensiveCoverage($pokemonToTestIds);

      $this->assertEquals($testedTeam->getDefensiveCover()['Vol']['result'], 'vulnerable');
      $this->assertEquals($testedTeam->getDefensiveCover()['Feu']['result'], 'slightly-vulnerable');
      $this->assertEquals($testedTeam->getDefensiveCover()['Acier']['result'], 'balanced');
      $this->assertEquals($testedTeam->getDefensiveCover()['Plante']['result'], 'resistant');
    }

    public function testSuggestion()
    {

      $pokemonToTestIds = [15, 272, 400];

      $testedSuggestions = $this->pokemonService->suggestPokemon($pokemonToTestIds);

      foreach($testedSuggestions as $suggestedPokemon){
        $this->assertContains($suggestedPokemon->getResistances()['Vol']['damage_multiplier'], [0, 0.25, 0.5]);
        $this->assertContains($suggestedPokemon->getResistances()['Psy']['damage_multiplier'], [0, 0.25, 0.5]);
        $this->assertNotContains($suggestedPokemon->getResistances()['Glace']['damage_multiplier'], [4]);
        $this->assertNotContains($suggestedPokemon->getResistances()['Poison']['damage_multiplier'], [2, 4]);
      }
    }

}
