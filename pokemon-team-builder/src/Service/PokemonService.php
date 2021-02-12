<?php

namespace App\Service;

use App\Entity\Pokemon;
use App\Repository\TypeRepository;

class PokemonService
{
  private $typeRepository;

  public function __construct(TypeRepository $typeRepository)
  {
    $this->typeRepository = $typeRepository;
  }

  public function calculateResistances(Pokemon $pokemon)
  {
    $pokemonTypes = $pokemon->getTypes();

    $allResistances = [];

    foreach($this->typeRepository->findAll() as $testedType){

      $damageMultiplier = 1;

      foreach($pokemonTypes as $type){

        $vulnerabilities = [];

        $vulnerablesTypes = $type->getVulnerableTo();

        foreach($vulnerablesTypes as $vulnerablesType){

          array_push($vulnerabilities, $vulnerablesType);

        }

        $resistances = [];

        $resistantTypes = $type->getResistantTo();

        foreach($resistantTypes as $resistantType){

          array_push($resistances, $resistantType);

        }

        $immunities = [];

        $immunityTypes = $type->getImmuneTo();

        foreach($immunityTypes as $immunityType){

          array_push($immunities, $immunityType);

        }

        if(in_array($testedType, $vulnerabilities)){

          $damageMultiplier *= 2;
        }
        elseif(in_array($testedType, $resistances)){

          $damageMultiplier /= 2;
        }
        elseif(in_array($testedType, $immunities)){

          $damageMultiplier = 0;
        }

        
      }
        if($damageMultiplier == 1){

          $damage_relation = 'neutral';
        }
        elseif($damageMultiplier == 2){

          $damage_relation = 'vulnerable';
        }
        elseif($damageMultiplier == 4){

          $damage_relation = 'twice_vulnerable';
        }
        elseif($damageMultiplier == 0.5){

          $damage_relation = 'resistant';
        }
        elseif($damageMultiplier == 0.25){

          $damage_relation = 'twice_resistant';
        }
        elseif($damageMultiplier == 0){

          $damage_relation = 'immune';
        }
        $typeResistance = [
                            'damage_multiplier' => $damageMultiplier,
                            'damage_relation' => $damage_relation
                          ];
        
        $allResistances[$testedType->getName()] = $typeResistance;
    }

    $pokemon->setResistances($allResistances);
  }
}