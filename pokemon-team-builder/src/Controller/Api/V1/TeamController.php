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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/v1/admin/team", name="api_v1_admin_team_")
 */

class TeamController extends AbstractController
{
    /**
     * @Route("/creation", name="creation", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository, 
    PokemonRepository $pokemonRepository, TranslatorInterface $translator): Response
    {
        $newTeamInfo = json_decode($request->getContent(), true);
  
        $teamToAdd = new Team();
  
        $teamToAdd->setName($newTeamInfo['name']);
        $userName = $newTeamInfo['username'];

        $user = $userRepository->findOneBy(['username' => $userName]);

        if($user == null){

            return $this->json($translator->trans('wrong-username', [], 'messages'), 400);
        }

        $teamToAdd->setUser($user);

        $pokemonList = $newTeamInfo['pokemon'];

        if (count($pokemonList) < 6) {
            return $this->json($translator->trans('too-few-pokemon', [], 'messages'), 400);
        } 

        elseif (count($pokemonList) > 6) {
            return $this->json($translator->trans('too-much-pokemon', [], 'messages'), 400);
        } 

        foreach ($pokemonList as $pokemon) {

            $pokemonToAdd[] = $pokemonRepository->find($pokemon); 

            foreach ($pokemonToAdd as $pokemon) {

                if ($pokemon == null){

                    return $this->json($translator->trans('wrong-pokemon', [], 'messages'), 400);
                }

                $teamToAdd->addPokemon($pokemon);
            }
        }
  
        $em->persist($teamToAdd);
  
        $em->flush();

        return $this->json($translator->trans('team-creation', ['team' => $teamToAdd->getName()], 'messages'));
        
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
    public function showById(Team $team, PokemonService $pokemonService, PokemonRepository $pokemonRepository): Response
    {
       
        foreach ($team->getPokemon() as $pokemon){
            $pokemonService->calculateResistances($pokemon);
            $pokemonTeam[] = $pokemonRepository->find($pokemon);
          }
   
        return $this->json([
            $team,
            $pokemonTeam
        ]);

    }

    /**
     * @Route("/show/{name}", name="show_by_name", requirements={"name"="\w+"}, methods={"GET"})
     */
    public function showByName(Team $team, PokemonService $pokemonService, $name, TeamRepository $teamRepository,
    PokemonRepository $pokemonRepository): Response
    {
        $teamByName = $teamRepository->findOneBy(['name' => $name]);
        foreach ($team->getPokemon() as $pokemon){
            $pokemonService->calculateResistances($pokemon);
            $pokemonTeam[] = $pokemonRepository->find($pokemon);
          }
   
        return $this->json([
            $teamByName,
            $pokemonTeam
        ]);

    }


    /**
     * @Route("/edit/{id}", name="edit", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, TeamRepository $teamRepository,
    PokemonRepository $pokemonRepository, TranslatorInterface $translator, Team $team): Response
    {
        $amendedTeamInfo = json_decode($request->getContent(), true);

        $teamToAmend = $teamRepository->find($team);

        $newTeamName = $amendedTeamInfo['name'];
     
        $teamToAmend->setName($newTeamName);

        $pokemonToAmend = $teamToAmend->getPokemon();

        foreach ($pokemonToAmend as $pokemon) {
            $teamToAmend->removePokemon($pokemon);
        }
    
       
        $pokemonList = $amendedTeamInfo['pokemon'];

        if (count($pokemonList) < 6) {
            return $this->json($translator->trans('too-few-pokemon', [], 'messages'), 400);
        } 

        elseif (count($pokemonList) > 6) {
            return $this->json($translator->trans('too-much-pokemon', [], 'messages'), 400);
        } 

        foreach ($pokemonList as $pokemon) {
            
            $pokemonToAdd[] = $pokemonRepository->find($pokemon); 

            foreach ($pokemonToAdd as $pokemon) {

                if ($pokemon == null){

                    return $this->json($translator->trans('wrong-pokemon', [], 'messages'), 400);
                }

                $teamToAmend->addPokemon($pokemon);
            }
        }
  
        $em->flush();

        return $this->json($translator->trans('team-edition', ['team' => $teamToAmend->getName()], 'messages'));
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function delete(Request $request, Team $team, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {

        $em->remove($team);
        $em->flush();
    
        return $this->json($translator->trans('team-deletion', ['team' => $team->getName()], 'messages')); 
             

    }

    

    
    
}
