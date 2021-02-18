<?php

namespace App\Service;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class PokemonService
{
  private $typeRepository;
  private $pokemonRepository;
  private $translator;

  public function __construct(TypeRepository $typeRepository, PokemonRepository $pokemonRepository, TranslatorInterface $translator)
  {
    $this->typeRepository = $typeRepository;
    $this->pokemonRepository = $pokemonRepository;
    $this->translator = $translator;
  }
  /**
   * Calculate the resistances of the pokemon and fills its 'resistances' atrributes with data contained its relation ith each type
   *
   * @param Pokemon $pokemon
   * @return void
   */
  public function calculateResistances(Pokemon $pokemon)
  { 
    //first, we get all the pokemon's types (1 or 2)
    $pokemonTypes = $pokemon->getTypes();

    $allResistances = [];
    //we have have to compare each existing type to each type the pokemon has
    foreach($this->typeRepository->findAll() as $testedType){
      //by default, damage does 'normal' damage, ot 100% of damage
      $damageMultiplier = 1;

      foreach ($pokemonTypes as $type) {
          //we register each chson pokemon type's resistances, vulnerabilities and immunities
          $vulnerabilities = [];

          $vulnerablesTypes = $type->getVulnerableTo();

          foreach ($vulnerablesTypes as $vulnerablesType) {
              array_push($vulnerabilities, $vulnerablesType);
          }

          $resistances = [];

          $resistantTypes = $type->getResistantTo();

          foreach ($resistantTypes as $resistantType) {
              array_push($resistances, $resistantType);
          }

          $immunities = [];

          $immunityTypes = $type->getImmuneTo();

          foreach ($immunityTypes as $immunityType) {
              array_push($immunities, $immunityType);
          }

          if (in_array($testedType, $vulnerabilities)) {
              // damage is doubled when a type is vulnerable to another one
              $damageMultiplier *= 2;
          } elseif (in_array($testedType, $resistances)) {
              // damage is halved  when a type is resistant to another one
              $damageMultiplier /= 2;
          } elseif (in_array($testedType, $immunities)) {
              // damage is nullified if a type is immune to another. In the case of a pokemon with two types, the other, non-immune, type is ignored
              $damageMultiplier = 0;
          }
      }

        //the result oh this operation generates, for each type, a damage multiplier that takes into consideration the resistances the one or both types the pokemon has. We list each different possible scenario and provide a description for it.
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
        //then we provide for the pokemon the multiplier and description of its resistance with each type
        $typeResistance = [ 
                            'name' => $testedType->getName(),
                            'damage_multiplier' => $damageMultiplier,
                            'damage_relation' => $damage_relation
                          ];
        
        $allResistances[$testedType->getName()] = $typeResistance;
    }

    $pokemon->setResistances($allResistances);
  }
}