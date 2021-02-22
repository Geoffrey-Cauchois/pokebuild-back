<?php

namespace App\Controller\Api\V1;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/api/v1/team/creation", name="team_creation", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $newTeamInfo = json_decode($request->getContent(), true);
  
        $teamToAdd = new Team();
  
        $teamToAdd->setName($newTeamInfo['name']);
  
        $em->persist($teamToAdd);
  
        $em->flush();
  
          return $this->json('Equipe ' . $teamToAdd->getName() . ' enregistrée');
    }
    
}
