<?php

namespace App\Controller\Api\V1;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/api/v1/error", name="api_v1_error")
     */
    public function error404(PokemonRepository $pokemonRepository): Response
    {
        $pokemon404 = $pokemonRepository->find(404);
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'pokemon404' => $pokemon404,
        ]);
    }
}
