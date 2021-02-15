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
     * @Route("/pokemon/type/{name}", name="pokemon_type_by_name", requirements={"name"="\w+"}, methods={"GET"})
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



}
