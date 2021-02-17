<?php

namespace App\Controller\Api\V1;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/api/v1", name="api_v1_")
     */
    public function index(): Response
    {
        
        return $this->render('api/v1/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
