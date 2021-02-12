<?php

namespace App\Controller\Api\V1;

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

    
}
