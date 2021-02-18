<?php

namespace App\Controller\Api\V1;

use App\Form\TeamType;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/api/v1", name="api_v1_")
     */
    public function index(PokemonRepository $pokemonRepository): Response
    {
        $allPokemon = $pokemonRepository->findAll();

        return $this->render('api/v1/main/index.html.twig', [
            'pokemons' => $allPokemon,
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
     * @Route("/defensiveCoverTest", name="defensie-cover-test")
     */
    public function defensieCoverageTest(Request $request, HttpClientInterface $client)
    {
      $form = $this->createForm(TeamType::class);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        $team = $form->getData();

        $chosenPokemon = $team->getPokemon();

        foreach($chosenPokemon as $pokemon){

          $chosenPokemonIds[] = $pokemon->getId();
        }

        $chosenPokemonIds = json_encode($chosenPokemonIds);

        dump($chosenPokemonIds);

        $opts = ['http' => [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $chosenPokemonIds
        ]];

        $context = stream_context_create($opts);

        $processedCoverage = file_get_contents('http://ec2-3-83-51-192.compute-1.amazonaws.com/api/v1/team/defensive-coverage', false, $context);

        $decodedProcessedCoverage = json_decode($processedCoverage);

        return $this->json($decodedProcessedCoverage);
      }

      return $this->render('api/v1/main/defensiveCoverage.html.twig', [
        'form' => $form->createView()
    ]);
    }
}
