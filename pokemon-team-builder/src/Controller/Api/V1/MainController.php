<?php

namespace App\Controller\Api\V1;

use App\Form\TeamType;
use App\Repository\PokemonRepository;
use App\Repository\ResistanceModifyingAbilitiesRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/api/v1", name="api_v1_")
     */
    public function index(PokemonRepository $pokemonRepository, Slugger $slugger, TypeRepository $typeRepository, ResistanceModifyingAbilitiesRepository $resistanceModifyingAbilitiesRepository): Response
    {
        $allPokemon = $pokemonRepository->findAll();
        $testPokemon = $pokemonRepository->find(250);
        $slugger->sluggifyPokemon($testPokemon);
        $carouselStart = rand(1,898);
        $pokemon7 = $pokemonRepository->find(7);
        $pokemon10 = $pokemonRepository->find(10);
        $pokemon16 = $pokemonRepository->find(16);
        $pokemon46 = $pokemonRepository->find(46);
        $pokemon54 = $pokemonRepository->find(54);
        $pokemon55 = $pokemonRepository->find(55);
        $pokemon56 = $pokemonRepository->find(56);
        $pokemon57 = $pokemonRepository->find(57);
        $pokemon81 = $pokemonRepository->find(81);
        $pokemon92 = $pokemonRepository->find(92);
        $pokemon110 = $pokemonRepository->find(110);
        $pokemon131 = $pokemonRepository->find(131);
        $pokemon197 = $pokemonRepository->find(197);
        $pokemon205 = $pokemonRepository->find(205);
        $pokemon300 = $pokemonRepository->find(300);
        $pokemon301 = $pokemonRepository->find(301);
        $pokemon302 = $pokemonRepository->find(302);
        $pokemon395 = $pokemonRepository->find(395);
        $pokemon400 = $pokemonRepository->find(400);
        $pokemon417 = $pokemonRepository->find(417);
        $pokemon446 = $pokemonRepository->find(446);
        $pokemon463 = $pokemonRepository->find(463);
        $pokemon485 = $pokemonRepository->find(485);
        $pokemon498 = $pokemonRepository->find(498);
        $pokemon850 = $pokemonRepository->find(850);   
        
        $type4 = $typeRepository->find(40);
        $type10 = $typeRepository->find(46);
        $type14 = $typeRepository->find(50);
        $sol = $typeRepository->find(41);

        $allSkils = $resistanceModifyingAbilitiesRepository->findAll() ;

        return $this->render('api/v1/main/index.html.twig', [
            'pokemons' => $allPokemon,
            'carouselStart' => $carouselStart,
            'pokemon417' => $pokemon417,
            'pokemon7' => $pokemon7,
            'pokemon850' => $pokemon850,
            'pokemon92' => $pokemon92,
            'pokemon10' => $pokemon10,
            'pokemon197' => $pokemon197,
            'pokemon498' => $pokemon498,
            'pokemon300' => $pokemon300,
            'pokemon301' => $pokemon301,
            'pokemon302' => $pokemon302,
            'pokemon54' => $pokemon54,
            'pokemon55' => $pokemon55,
            'pokemon56' => $pokemon56,
            'pokemon57' => $pokemon57,
            'pokemon400' => $pokemon400,
            'pokemon46' => $pokemon46,
            'pokemon205' => $pokemon205,
            'pokemon81' => $pokemon81,
            'pokemon395' => $pokemon395,
            'pokemon131' => $pokemon131,
            'pokemon485' => $pokemon485,
            'pokemon16' => $pokemon16,
            'pokemon446' => $pokemon446,
            'pokemon463' => $pokemon463,
            'type4'=> $type4,
            'type10' => $type10,
            'type14' => $type14,
            'skills' => $allSkils,
            'sol' => $sol,
            'pokemon110' => $pokemon110
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): RedirectResponse
    {
      return $this->redirectToRoute('api_v1_');
    }

    /**
     * @Route("/defensiveCoverTest", name="defensive-cover-test")
     */
    public function defensiveCoverageTest(Request $request, TranslatorInterface $translator)
    {
      $form = $this->createForm(TeamType::class);

      $form->handleRequest($request);
      
      $noticeStart = $translator->trans('coverage-notice-start', [], 'messages');

      $noticeUrl = $translator->trans('coverage-notice-url', [], 'messages');

      $noticeEnd = $translator->trans('coverage-notice-end', [], 'messages');

      if($form->isSubmitted() && $form->isValid()){

        $team = $form->getData();

        $chosenPokemon = $team->getPokemon();

        $chosenPokemonData = [];

        foreach($chosenPokemon as $pokemon){

          if($request->request->get(strtolower($pokemon->getName()) . 'Ability') != null && $request->request->get(strtolower($pokemon->getName()) . 'Ability') != 'null'){

            $chosenSkill = $request->request->get(strtolower($pokemon->getName()) . 'Ability');
          }
          else{

            $chosenSkill = null;
          }

          $pokemonData = [$pokemon->getId() => $chosenSkill];

          array_push($chosenPokemonData, $pokemonData);

        }

        $chosenPokemonData = json_encode($chosenPokemonData);
        
        $opts = ['http' => [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $chosenPokemonData
        ]];

        $context = stream_context_create($opts);

        $processedCoverage = file_get_contents($request->server->get('API_BASE_URL') . '/team/defensive-coverage/v2', false, $context);

        $decodedProcessedCoverage = json_decode($processedCoverage);

        if (isset($decodedProcessedCoverage[0]->summary)){

          unset($decodedProcessedCoverage[0]->summary);
        }

        if (isset($decodedProcessedCoverage[0]->class)){

          unset($decodedProcessedCoverage[0]->class);
        }

        return $this->json($decodedProcessedCoverage);
      }

      return $this->render('api/v1/main/defensiveCoverage.html.twig', [
        'form' => $form->createView(),
        'noticeStart' => $noticeStart,
        'noticeUrl' => $noticeUrl,
        'noticeEnd' => $noticeEnd
    ]);
    }

    /**
     * @Route("/suggestionTest", name="suggestion-test")
     */
    public function suggestionTest(Request $request, TranslatorInterface $translator)
    {
      $form = $this->createForm(TeamType::class);

      $form->handleRequest($request);

      $noticeStart = $translator->trans('suggestion-notice-start', [], 'messages');

      $noticeUrl = $translator->trans('suggestion-notice-url', [], 'messages');

      $noticeEnd = $translator->trans('suggestion-notice-end', [], 'messages');

      if($form->isSubmitted() && $form->isValid()){

        $team = $form->getData();

        $chosenPokemon = $team->getPokemon();

        $chosenPokemonData = [];

        foreach($chosenPokemon as $pokemon){

          if($request->request->get(strtolower($pokemon->getName()) . 'Ability') != null && $request->request->get(strtolower($pokemon->getName()) . 'Ability') != 'null'){

            $chosenSkill = $request->request->get(strtolower($pokemon->getName()) . 'Ability');
          }
          else{

            $chosenSkill = null;
          }

          $pokemonData = [$pokemon->getId() => $chosenSkill];

          array_push($chosenPokemonData, $pokemonData);
        }

        $chosenPokemonData = json_encode($chosenPokemonData);

        $opts = ['http' => [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $chosenPokemonData
        ]];

        $context = stream_context_create($opts);

        $processedSuggestion = file_get_contents($request->server->get('API_BASE_URL') . '/team/suggestion/v2', false, $context);

        $decodedProcessedSuggestion = json_decode($processedSuggestion);

        return $this->json($decodedProcessedSuggestion);
      }

      return $this->render('api/v1/main/defensiveCoverage.html.twig', [
        'form' => $form->createView(),
        'noticeStart' => $noticeStart,
        'noticeUrl' => $noticeUrl,
        'noticeEnd' => $noticeEnd
    ]);
    }
}
