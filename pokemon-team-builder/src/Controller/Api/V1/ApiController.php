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
     * @Route("/list", name="list", methods={"GET"})
     */

    public function list(PokemonRepository $pokemonRepository): Response
    {
        $pokemons = $pokemonRepository->findAll();

        return $this->json($pokemons);
    }

    /**
     * @Route("/{id}", name="show_by_id", methods={"GET"})
     */
    public function read(Pokemon $pokemon): Response
    {
        return $this->json($pokemon);
    }



}
