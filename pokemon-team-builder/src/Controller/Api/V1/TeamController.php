<?php

namespace App\Controller\Api\V1;

use App\Entity\Team;
use App\Repository\PokemonRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\PokemonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/team", name="api_v1_team_")
 */


class TeamController extends AbstractController
{
    /**
     * @Route("/creation", name="creation", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository, 
    PokemonRepository $pokemonRepository): Response
    {
        $newTeamInfo = json_decode($request->getContent(), true);
  
        $teamToAdd = new Team();
  
        $teamToAdd->setName($newTeamInfo['name']);
        $userName = $newTeamInfo['username'];
        $user = $userRepository->findOneBy(['username' => $userName]);
        $teamToAdd->setUser($user);
        $pokemonList = $newTeamInfo['pokemon'];
        foreach ($pokemonList as $pokemon) {
            
            $pokemonToAdd[] = $pokemonRepository->find($pokemon); 

            foreach ($pokemonToAdd as $pokemon) {

                $teamToAdd->addPokemon($pokemon);
            }
        }
  
        $em->persist($teamToAdd);
  
        $em->flush();

        return $this->json('Equipe ' . $teamToAdd->getName() . ' enregistrée');
        
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(TeamRepository $teamRepository): Response
    {
        
        $allTeams = $teamRepository->findAll();
        return $this->json($allTeams);

    }

    /**
     * @Route("/show/{id}", name="show_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(Team $team, PokemonService $pokemonService): Response
    {
        foreach ($team->getPokemon() as $pokemon){
            $pokemonService->calculateResistances($pokemon);
          }
   
        return $this->json($team);

    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, TeamRepository $teamRepository,
    PokemonRepository $pokemonRepository): Response
    {
        $amendedTeamInfo = json_decode($request->getContent(), true);
        $teamToAmendId = $amendedTeamInfo['id'];

        $newTeamName = $amendedTeamInfo['name'];
        
        $teamToAmend = $teamRepository->findOneWithPokemon($teamToAmendId);

        $teamToAmend->setName($newTeamName);

        $pokemonToAmend = $teamToAmend->getPokemon();

        foreach ($pokemonToAmend as $pokemon) {
            $teamToAmend->removePokemon($pokemon);
        }
    
       
        $pokemonList = $amendedTeamInfo['pokemon'];
        foreach ($pokemonList as $pokemon) {
            
            $pokemonToAdd[] = $pokemonRepository->find($pokemon); 

            foreach ($pokemonToAdd as $pokemon) {

                $teamToAmend->addPokemon($pokemon);
            }
        }
  
        $em->flush();

        return $this->json('Equipe ' . $teamToAmend->getName() . ' modifiée');
    }

    

    
    
}
