<?php

namespace App\Controller\Api\V1;

use App\Repository\PokemonRepository;
use App\Service\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomTeamController extends AbstractController
{
    
    /**
     * @Route("/api/v1/random/team", name="api_v1_random_team", methods={"GET"})
     */
    public function random(PokemonRepository $pokemonRepository, PokemonService $pokemonService): Response
    {

        $randomTeam = [];
        for ($i = 0; $i < 7; $i ++) {
            $randomPokemon = $pokemonRepository->find(rand(1,898));
                if (!in_array($randomPokemon, $randomTeam)) {
                    
                    $randomTeam[] = $randomPokemon;
                }         
        }

        foreach ($randomTeam as $pokemon){
            $pokemonService->calculateResistances($pokemon);
        }

        return $this->json($randomTeam);
     
    }
}
