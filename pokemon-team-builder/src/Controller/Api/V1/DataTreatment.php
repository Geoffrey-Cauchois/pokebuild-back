<?php

namespace App\Controller\Api\V1;

use App\Entity\Team;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use App\Service\PokemonService;
use PhpParser\Node\Stmt\Foreach_;
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
    public function suggestPokemon(Request $request, PokemonService $pokemonService, TranslatorInterface $translator, PokemonRepository $pokemonRepository): Response
    {
        $chosenPokemonIds = json_decode($request->getContent(), false);

        if (!is_array($chosenPokemonIds) || count($chosenPokemonIds) <= 0) {
          return $this->json($translator->trans('empty-team', [], 'messages'), 400);
        }
        elseif (count($chosenPokemonIds) >= 6) {
          return $this->json($translator->trans('full-team', [], 'messages'), 400);
        }

        $team = $pokemonService->calculateDefensiveCoverage($chosenPokemonIds);

        $defensiveCoverage = $team->getDefensiveCover();

        $teamVulnerabilities =[];
        $teamSlightVulnerabilities = [];
        $teamNeutralities = [];
        $teamSlightResistances = [];
        $teamResistances = [];

        foreach ($defensiveCoverage as $type => $typeDefensiveData) {
            if ($typeDefensiveData['result'] == 'vulnerable') {
                $teamVulnerabilities[] = $type;
            } elseif ($typeDefensiveData['result'] == 'slightly-vulnerable') {
                $teamSlightVulnerabilities[] = $type;
            } elseif ($typeDefensiveData['result'] == 'balanced') {
                $teamNeutralities[] = $type;
            } elseif ($typeDefensiveData['result'] == 'resistant') {
                $teamResistances[] = $type;
            } elseif ($typeDefensiveData['result'] == 'slightly-resistant') {
                $teamSlightResistances[] = $type;
            }
        }


      $allPokemon = $pokemonRepository->findAll();

      $suggestionScores = [];

      foreach ($allPokemon as $pokemon) {
          $pokemonService->calculateResistances($pokemon);

          $suggestionScore = 0;
        
        foreach ($teamVulnerabilities as $vulnerableType) {

          if ($pokemon->getResistances()[$vulnerableType]['damage_multiplier'] < 0.5) {

            $suggestionScore += 4;
          }
          elseif ($pokemon->getResistances()[$vulnerableType]['damage_multiplier'] < 1) {

            $suggestionScore += 2;
          }
        }
        foreach ($teamSlightVulnerabilities as $sligthlyVulnerableType) {

          if ($pokemon->getResistances()[$sligthlyVulnerableType]['damage_multiplier'] < 1) {

            $suggestionScore += 1;               
          }
        }
        foreach ($teamNeutralities as $neutralType) {

          if ($pokemon->getResistances()[$neutralType]['damage_multiplier'] > 1) {

            $suggestionScore -= 2;
          }
          elseif ($pokemon->getResistances()[$neutralType]['damage_multiplier'] > 2) {

            $suggestionScore -= 4;
          }
        }                                   
        foreach ($teamSlightResistances as $slightlyResistantType) {

          if ($pokemon->getResistances()[$slightlyResistantType]['damage_multiplier'] > 2) {

            $suggestionScore -= 1;
          }
        }
        foreach ($teamResistances as $resisantType) {

          if ($pokemon->getResistances()[$resisantType]['damage_multiplier'] > 4) {

            $suggestionScore -= 1;
          }
        }

        $suggestionScores[$pokemon->getName()] = $suggestionScore;
      }

        $bestScore = max($suggestionScores);

        $suggestedPokemon =[];

        foreach($suggestionScores as $pokemonName => $score){

          if($score == $bestScore){
            array_push($suggestedPokemon, $pokemonRepository->findOneBy(['name' => $pokemonName]));
          }
        }

        foreach($suggestedPokemon as $suggestion){

          $pokemonService->calculateResistances($suggestion);
        }
        
        return $this->json($suggestedPokemon);
    }
}