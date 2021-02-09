<?php

namespace App\Controller;

use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(TypeRepository $typeRepository, EntityManagerInterface $em): Response
    {

      $types = $typeRepository->findAll();

      dump($types);

      $type = $typeRepository->find(2);

      $type->addImmuneTo($typeRepository->find(1));


      $em->flush();

      dump($type);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
