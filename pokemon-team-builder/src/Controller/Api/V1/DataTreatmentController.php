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
class DataTreatmentController extends AbstractController
{
    /**
     * @Route("/team/defensive-coverage", name="api_v1_defensive_coverage", methods={"POST"})
     */
    public function calculateDefensiveCoverage(Request $request, PokemonService $pokemonService, TranslatorInterface $translator): Response
    {
        $chosenPokemonIds = json_decode($request->getContent(), true);
        //returns a 400 error if to much, not enough or incorrect data is sent
        if (!is_array($chosenPokemonIds) || count($chosenPokemonIds) <= 0) {
          return $this->json($translator->trans('empty-team', [], 'messages'), 400);
        }
        elseif (count($chosenPokemonIds) >= 7) {
          return $this->json($translator->trans('too-much-pokemon', [], 'messages'), 400);
        }

        $team = $pokemonService->calculateDefensiveCoverage($chosenPokemonIds);

        if(count($chosenPokemonIds) == 6){

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

          if(count($teamVulnerabilities) + count($teamSlightVulnerabilities) > count($teamSlightResistances) + count($teamResistances)){

            $summary = $translator->trans('more-vulnerabilities', [], 'messages');

            $class = 'more-vulnerabilities';
          }
          elseif(count($teamVulnerabilities) + count($teamSlightVulnerabilities) == count($teamSlightResistances) + count($teamResistances)){

            $summary = $translator->trans('equal-resistances', [], 'messages');

            $class = 'equal-resistances';
          }
          else{
            
            if(count($teamVulnerabilities) + count($teamSlightVulnerabilities) < 2){

              $summary = $translator->trans('low-vulnerabilities', [], 'messages');

              $class = 'low-vulnerabilities';
            }
            else{

              $summary = $translator->trans('more-resistances', [], 'messages');

              $class = 'more-resistances';
            }
          }

        $coverToreturn = $team->getDefensiveCoverForApi();

        $coverToreturn[0]['summary'] = $summary;

        $coverToreturn[0]['class'] = $class;

        return $this->json($coverToreturn);
        } 
        else{

          return $this->json($team->getDefensiveCoverForApi());
        }

        
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

    /**
     * @Route("/team/defensive-coverage/v2", name="api_v1_defensive_coverage_v2", methods={"POST"})
     */
    public function calculateDefensiveCoverageV2(Request $request, PokemonService $pokemonService, TranslatorInterface $translator): Response
    {
        $chosenPokemonData = json_decode($request->getContent(), true);

        //returns a 400 error if to much, not enough or incorrect data is sent
        if (!is_array($chosenPokemonData) || count($chosenPokemonData) <= 0) {
          return $this->json($translator->trans('empty-team', [], 'messages'), 400);
        }
        elseif (count($chosenPokemonData) >= 7) {
          return $this->json($translator->trans('too-much-pokemon', [], 'messages'), 400);
        }

        foreach($chosenPokemonData as $pokemonData){

          foreach($pokemonData as $id => $skillName){
            $chosenPokemonIds[] = $id;
          $chosenSkillsNames[] = $skillName;
          }
        }

        $team = $pokemonService->calculateDefensiveCoverage($chosenPokemonIds, $chosenSkillsNames);

        if(count($chosenPokemonData) == 6){

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

          if(count($teamVulnerabilities) + count($teamSlightVulnerabilities) > count($teamSlightResistances) + count($teamResistances)){

            $summary = $translator->trans('more-vulnerabilities', [], 'messages');

            $class = 'more-vulnerabilities';
          }
          elseif(count($teamVulnerabilities) + count($teamSlightVulnerabilities) == count($teamSlightResistances) + count($teamResistances)){

            $summary = $translator->trans('equal-resistances', [], 'messages');

            $class = 'equal-resistances';
          }
          else{
            
            if(count($teamVulnerabilities) + count($teamSlightVulnerabilities) < 2){

              $summary = $translator->trans('low-vulnerabilities', [], 'messages');

              $class = 'low-vulnerabilities';
            }
            else{

              $summary = $translator->trans('more-resistances', [], 'messages');

              $class = 'more-resistances';
            }
          }

        $coverToreturn = $team->getDefensiveCoverForApi();

        $coverToreturn[0]['summary'] = $summary;

        $coverToreturn[0]['class'] = $class;

        return $this->json($coverToreturn);
        } 
        else{

          return $this->json($team->getDefensiveCoverForApi());
        }

        
    }

    /**
     * @Route("/team/suggestion/v2", name="api_v1_pokemon_suggestion_v2", methods={"POST"})
     */
    public function suggestPokemonV2(Request $request, PokemonService $pokemonService, TranslatorInterface $translator): Response
    {
       
      $chosenPokemonData = json_decode($request->getContent(), true);
      //returns a 400 error if to much, not enough or incorrect data is sent
      if (!is_array($chosenPokemonData) || count($chosenPokemonData) <= 0) {
        return $this->json($translator->trans('empty-team', [], 'messages'), 400);
      }
      elseif (count($chosenPokemonData) >= 6) {
        return $this->json($translator->trans('full-team', [], 'messages'), 400);
      }

      foreach($chosenPokemonData as $pokemonData){

        foreach($pokemonData as $id => $skillName){
          $chosenPokemonIds[] = $id;
        $chosenSkillsNames[] = $skillName;
        }
      }

      $suggestedPokemon = $pokemonService->suggestPokemon($chosenPokemonIds, $chosenSkillsNames);
      
      return $this->json($suggestedPokemon);
    }

}