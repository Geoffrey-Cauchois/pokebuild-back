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
    public function suggestPokemon(Request $request, PokemonService $pokemonService, TranslatorInterface $translator, PokemonRepository $pokemonRepository): Response
    {
        $chosenPokemonIds = json_decode($request->getContent(), false);
        //returns a 400 error if to much, not enough or incorrect data is sent
        if (!is_array($chosenPokemonIds) || count($chosenPokemonIds) <= 0) {
          return $this->json($translator->trans('empty-team', [], 'messages'), 400);
        }
        elseif (count($chosenPokemonIds) >= 6) {
          return $this->json($translator->trans('full-team', [], 'messages'), 400);
        }
        //first, we need to calculate the team's defensive coverage, the team will then have a resistance status for each type
        $team = $pokemonService->calculateDefensiveCoverage($chosenPokemonIds);

        $defensiveCoverage = $team->getDefensiveCover();
        //we store all types in separates arrays depending on how the team is resistant to them
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
      // to be able to provide the best poemon suggestion, all pokemon will be tested
      foreach ($allPokemon as $pokemon) {
          $pokemonService->calculateResistances($pokemon);
          // pokemon will be attributed a score
          $suggestionScore = 0;
        
        foreach ($teamVulnerabilities as $vulnerableType) {
          //the main objective is to suggest pokemon that reduce or compensate the team's vulnerabilities. pokemon that have double resistances or immunities to a team vulnerability will gain 4 points, pokemon that resist to a team vulnerability will gein 2 points
          if ($pokemon->getResistances()[$vulnerableType]['damage_multiplier'] < 0.5) {

            $suggestionScore += 4;
          }
          elseif ($pokemon->getResistances()[$vulnerableType]['damage_multiplier'] < 1) {

            $suggestionScore += 2;
          }
        }
        foreach ($teamSlightVulnerabilities as $sligthlyVulnerableType) {
          // although it can be good to also compensate slight vulnerabilities, it is not the priority, therefore pokemon that have resitances, double resistances or immunoties to a yteam slight vulnerability will gain 1 point
          if ($pokemon->getResistances()[$sligthlyVulnerableType]['damage_multiplier'] < 1) {

            $suggestionScore += 1;               
          }
        }
        foreach ($teamNeutralities as $neutralType) {
          // created new vulnerabilies to a team is case we want to avoid, it would not be the bast suggestion to compensate a vulnerability if we create another one in the process, therefore, pokemon that are vulnerable to a team neutrality will lose 2 points, or 4 points in case of a double vulnerability
          if ($pokemon->getResistances()[$neutralType]['damage_multiplier'] > 1) {

            $suggestionScore -= 2;
          }
          elseif ($pokemon->getResistances()[$neutralType]['damage_multiplier'] > 2) {

            $suggestionScore -= 4;
          }
        }                                   
        foreach ($teamSlightResistances as $slightlyResistantType) {
          //a pokemon that have a double vulnerability to a team slight resistance will transform it into a slight vulnerability. This is not the best case scenario but it can be accepted if it is needed to compensated the vulnerabilities. In this case, a double vulnerable pokemon will lose 1 point
          if ($pokemon->getResistances()[$slightlyResistantType]['damage_multiplier'] > 2) {

            $suggestionScore -= 1;
          }
        }
          //transforming a resistance or a slight resistance into a neutrality is acceptable, and often needed to balance a team's resistances. A pokemon will not lose points as long as its vulnerabilities do not cerate a team new vulnerability or slight vulnerability
        foreach ($teamResistances as $resisantType) {
          //currently, there are no greater vulnerabilities than double vulnerabilities, this case is created if a greater vulnerability will come in the future, but is not used yet
          if ($pokemon->getResistances()[$resisantType]['damage_multiplier'] > 4) {

            $suggestionScore -= 1;
          }
        }
        //after each type is tested, each pokemon will get a final score
        $suggestionScores[$pokemon->getName()] = $suggestionScore;
      }
        //then, we check all scores and we recuparate the geatest score
        $bestScore = max($suggestionScores);

        $suggestedPokemon =[];
        //al pokemon that have the best score will be suggested
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