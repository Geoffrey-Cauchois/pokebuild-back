<?php

namespace App\Controller\Api\V1;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
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

    public function list(PokemonRepository $pokemonRepository): Response
    {
        $pokemons = $pokemonRepository->findAll();

        return $this->json($pokemons);
    }

    /**
     * @Route("/pokemon/{id}", name="pokemon_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showById(Pokemon $pokemon): Response
    {
        return $this->json($pokemon);
    }

    /**
     * @Route("/pokemon/{name}", name="pokemon_by_name", requirements={"name"="\w+"}, methods={"GET"})
     */
    public function showByName(PokemonRepository $pokemonRepository, $name): Response
    {
        $pokemon = $pokemonRepository->findOneBy(['name' => $name]);
        return $this->json($pokemon);
    }



}
