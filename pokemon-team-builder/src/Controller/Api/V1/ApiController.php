<?php

namespace App\Controller\Api\V1;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use App\Service\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1", name="api_v1_")
 */

class ApiController extends AbstractController
{
    /**
     * @Route("/pokemon", name="pokemon", methods={"GET"})
     */

    public function list(PokemonRepository $pokemonRepository, PokemonService $pokemonService): Response
    {
        $pokemons = $pokemonRepository->findAll();

        foreach($pokemons as $pokemon){

          $pokemonService->calculateResistances($pokemon);
        }

        return $this->json($pokemons);
    }

    /**
     * @Route("/pokemon/{id}", name="pokemon_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showPokemonById(Pokemon $pokemon, PokemonService $pokemonService): Response
    {
        $pokemonService->calculateResistances($pokemon);

        return $this->json($pokemon);
    }

    /**
     * @Route("/pokemon/{name}", name="pokemon_by_name", requirements={"name"="\w+"}, methods={"GET"})
     */
    public function showPokemonByName(PokemonRepository $pokemonRepository, $name, PokemonService $pokemonService): Response
    {
        $pokemonByName = $pokemonRepository->findOneBy(['name' => $name]);

        $pokemonService->calculateResistances($pokemonByName);

        return $this->json($pokemonByName);
    }

    /**
     * @Route("/pokemon/type/{name}", name="pokemon_type_by_type", requirements={"name"="\w+"}, methods={"GET"})
     */
    public function showPokemonByType(PokemonRepository $pokemonRepository, $name, PokemonService $pokemonService): Response
    {
      $pokemonsByType = $pokemonRepository->findByType($name);

      foreach($pokemonsByType as $pokemon){

        $pokemonService->calculateResistances($pokemon);
      }

        return $this->json($pokemonsByType);
        
    }

    /**
     * @Route("/pokemon/generation/{id}", name="pokemon_generation_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showPokemonByGeneration(PokemonRepository $pokemonRepository, $id, PokemonService $pokemonService): Response
    {
        $pokemonsByGeneration = $pokemonRepository->findBy(['generation' => $id]);

        foreach($pokemonsByGeneration as $pokemon){

          $pokemonService->calculateResistances($pokemon);
        }  

        return $this->json($pokemonsByGeneration);
    }
    
    /**
    * @Route("/pokemon/limit/{number}", name="pokemon_limit", requirements={"number"="\d+"}, methods={"GET"})
    */
   public function showPokemonByLimit(PokemonRepository $pokemonRepository, $number, PokemonService $pokemonService): Response
   {
       $pokemonsByLimit = $pokemonRepository->findByLimit($number);

       foreach($pokemonsByLimit as $pokemon){

         $pokemonService->calculateResistances($pokemon);
       }  

       return $this->json($pokemonsByLimit);
   }


    /**
     * @Route("/pokemon/types/{typeName1}/{typeName2}", name="pokemon_by_double_type", requirements={"typeName1"="\w+", "typeName2"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByDoubleType(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName1, $typeName2): Response
    {
      $pokemonByTypes = $pokemonRepository->findByTypes($typeName1, $typeName2);

      foreach($pokemonByTypes as $pokemon){

        $pokemonService->calculateResistances($pokemon);
      }

      return $this->json($pokemonByTypes);
    }

    /**
     * @Route("/pokemon/type/weakness/{typeName}", name="pokemon_by_weakness", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByWeakness(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
      $pokemonByWeakness = [];

      $allPokemon = $pokemonRepository->findAll();

      foreach($allPokemon as $pokemon){

        $pokemonService->calculateResistances($pokemon);

        if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 2){

          array_push($pokemonByWeakness, $pokemon);

        }
      }

      return $this->json($pokemonByWeakness);
    }

    /**
     * @Route("/pokemon/type/double-weakness/{typeName}", name="pokemon_by_double_weakness", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByDoubleWeakness(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
      $pokemonByDoubleWeakness = [];

      $allPokemon = $pokemonRepository->findAll();

      foreach($allPokemon as $pokemon){

        $pokemonService->calculateResistances($pokemon);

        if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 4){

          array_push($pokemonByDoubleWeakness, $pokemon);

        }
      }

      return $this->json($pokemonByDoubleWeakness);
    }

    /**
     * @Route("/pokemon/type/resistance/{typeName}", name="pokemon_by_resistance", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByResistance(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
      $pokemonByResistance = [];

      $allPokemon = $pokemonRepository->findAll();

      foreach($allPokemon as $pokemon){

        $pokemonService->calculateResistances($pokemon);

        if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0.5){

          array_push($pokemonByResistance, $pokemon);

        }
      }

      return $this->json($pokemonByResistance);
    }

    /**
     * @Route("/pokemon/type/double-resistance/{typeName}", name="pokemon_by_double_resistance", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByDoubleResistance(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
      $pokemonByDoubleResistance = [];

      $allPokemon = $pokemonRepository->findAll();

      foreach($allPokemon as $pokemon){

        $pokemonService->calculateResistances($pokemon);

        if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0.25){

          array_push($pokemonByDoubleResistance, $pokemon);

        }
      }

      return $this->json($pokemonByDoubleResistance);
    }

        /**
     * @Route("/pokemon/type/immunity/{typeName}", name="pokemon_by_immunity", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */
    public function showPokemonByImmunity(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
      $pokemonByImmunity = [];

      $allPokemon = $pokemonRepository->findAll();

      foreach($allPokemon as $pokemon){

        $pokemonService->calculateResistances($pokemon);

        if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0){

          array_push($pokemonByImmunity, $pokemon);

        }
      }

      return $this->json($pokemonByImmunity);
    }

}
