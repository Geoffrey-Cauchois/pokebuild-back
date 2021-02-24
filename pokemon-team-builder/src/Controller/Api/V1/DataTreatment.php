<?php

namespace App\Controller\Api\V1;

use App\Repository\PokemonRepository;
use App\Service\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class DataTreatment extends AbstractController
{
    /**
     * @Route("/team/defensive-coverage", name="api_v1_defensive_coverage", methods={"POST"})
     */
    public function calculateDefensiveCoverage(Request $request, PokemonService $pokemonService, TranslatorInterface $translator): Response
    {
        $chosenPokemonIds = json_decode($request->getContent(), false);
        //returns a 400 error if to much, not enough or incorrect data is sent
        if (!is_array($chosenPokemonIds) || count($chosenPokemonIds) <= 0) {
          return $this->json($translator->trans('empty-team', [], 'messages'), 400);
        }
        elseif (count($chosenPokemonIds) >= 7) {
          return $this->json($translator->trans('too-much-pokemon', [], 'messages'), 400);
        }

        $team = $pokemonService->calculateDefensiveCoverage($chosenPokemonIds);

        return $this->json($team->getDefensiveCoverForApi());
    }

    /**
     * @Route("/team/suggestion", name="api_v1_pokemon_suggestion", methods={"POST"})
     */
    public function suggestPokemon(Request $request, PokemonService $pokemonService, TranslatorInterface $translator): Response
    {
       
      $chosenPokemonIds = json_decode($request->getContent(), false);
      //returns a 400 error if to much, not enough or incorrect data is sent
      if (!is_array($chosenPokemonIds) || count($chosenPokemonIds) <= 0) {
        return $this->json($translator->trans('empty-team', [], 'messages'), 400);
      }
      elseif (count($chosenPokemonIds) >= 6) {
        return $this->json($translator->trans('full-team', [], 'messages'), 400);
      }

      $suggestedPokemon = $pokemonService->suggestPokemon($chosenPokemonIds);
      
      return $this->json($suggestedPokemon);
    }
}