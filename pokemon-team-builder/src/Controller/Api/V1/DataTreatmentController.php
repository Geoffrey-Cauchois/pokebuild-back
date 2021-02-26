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
        $chosenPokemonIds = json_decode($request->getContent(), false);
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
          }
          elseif(count($teamVulnerabilities) + count($teamSlightVulnerabilities) == count($teamSlightResistances) + count($teamResistances)){

            $summary = $translator->trans('equal-resistances', [], 'messages');
          }
          else{
            
            if(count($teamVulnerabilities) + count($teamSlightVulnerabilities) < 2){

              $summary = $translator->trans('low-vulnerabilities', [], 'messages');
            }
            else{

              $summary = $translator->trans('more-resistances', [], 'messages');
            }
          }

        $coverToreturn = $team->getDefensiveCoverForApi();

        $coverToreturn[0]['summary'] = $summary;

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
}