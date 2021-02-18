<?php

namespace App\Controller\Api\V1;

use App\Entity\Team;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
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
     * @Route("/team/defensive-coverage", name="api_v1_data_treatment", methods={"POST"})
     */
    public function calculateDefensiveCoverage(Request $request, PokemonRepository $pokemonRepository, PokemonService $pokemonService, TypeRepository $typeRepository, TranslatorInterface $translator): Response
    {
      $chosenPokemonIds = json_decode($request->getContent(), false);

      $team = new Team;

      foreach($chosenPokemonIds as $id){

        $pokemon = $pokemonRepository->find($id);
        
        $pokemonService->calculateResistances($pokemon);

        $team->addPokemon($pokemon);
      }

      $defensive_coverage = [];

      foreach($typeRepository->findAll() as $type){

        $teamResistanceScore = 0;

        foreach($team->getPokemon() as $teamPokemon){

          //dd($teamPokemon->getResistances());

          $testedTypeMultiplier = $teamPokemon->getResistances()[$type->getName()]['damage_multiplier'];

          if($testedTypeMultiplier == 0 || $testedTypeMultiplier == 0.25){

            $teamResistanceScore += 2;
          }
          elseif($testedTypeMultiplier == 0.5){

            $teamResistanceScore += 1;
          }
          elseif($testedTypeMultiplier == 2){

            $teamResistanceScore -= 1;
          }
          elseif($testedTypeMultiplier == 4){

            $teamResistanceScore -= 2;
          }

        }

        if ($teamResistanceScore < -1){

          $result = 'vulnerable';

          $message = $translator->trans('vulnerable', [], 'messages');
        }
        elseif($teamResistanceScore == -1){

          $result = 'slightly-vulnerable';

          $message = $translator->trans('slightly-vulnerable', [], 'messages');
        }
        elseif($teamResistanceScore == 0){

          $result = 'balanced';

          $message = $translator->trans('balanced', [], 'messages');
        }
        elseif($teamResistanceScore == 1){
           
          $result = 'slightly-resistant';

          $message = $translator->trans('slightly-resistant', [], 'messages');
        }
        elseif($teamResistanceScore > 1){

          $result = 'resistant';

          $message = $translator->trans('resistant', [], 'messages');
        }

        $defensive_coverage[$type->getName()] = [
                                                 'result' => $result,
                                                 'message' => $message
                                                ];
      }

      $team->setDefensiveCover($defensive_coverage);

        return $this->json($team->getDefensiveCoverForApi());
    }
}
