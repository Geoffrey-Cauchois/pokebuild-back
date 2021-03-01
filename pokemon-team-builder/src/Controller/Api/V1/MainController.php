<?php

namespace App\Controller\Api\V1;

use App\Form\TeamType;
use App\Repository\PokemonRepository;
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
    public function index(PokemonRepository $pokemonRepository, Slugger $slugger): Response
    {
        $allPokemon = $pokemonRepository->findAll();
        $testPokemon = $pokemonRepository->find(250);
        $slugger->sluggifyPokemon($testPokemon);
        $carouselStart = rand(1,898);

        return $this->render('api/v1/main/index.html.twig', [
            'pokemons' => $allPokemon,
            'carouselStart' => $carouselStart
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

        foreach($chosenPokemon as $pokemon){

          $chosenPokemonIds[] = $pokemon->getId();
        }

        $chosenPokemonIds = json_encode($chosenPokemonIds);

        

        $opts = ['http' => [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $chosenPokemonIds
        ]];

        $context = stream_context_create($opts);

        $processedCoverage = file_get_contents($request->server->get('API_BASE_URL') . '/team/defensive-coverage', false, $context);

        $decodedProcessedCoverage = json_decode($processedCoverage);

        if (isset($decodedProcessedCoverage[0]->summary)){

          unset($decodedProcessedCoverage[0]->summary);
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

        foreach($chosenPokemon as $pokemon){

          $chosenPokemonIds[] = $pokemon->getId();
        }

        $chosenPokemonIds = json_encode($chosenPokemonIds);

        $opts = ['http' => [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $chosenPokemonIds
        ]];

        $context = stream_context_create($opts);

        $processedSuggestion = file_get_contents($request->server->get('API_BASE_URL') . '/team/suggestion', false, $context);

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
