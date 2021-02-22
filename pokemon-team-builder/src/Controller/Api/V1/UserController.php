<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\PokemonRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\PokemonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user/create", name="user-create", methods={"POST"})
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {

      $newUserInfo = json_decode($request->getContent(), true);



      $userToAdd = new User();

      $userToAdd->setUsername($newUserInfo['username']);

      $userToAdd->setPassword($encoder->encodePassword($userToAdd, $newUserInfo['password']));

      $newUserInfo['password'] = null;

      $userToAdd->setEmail($newUserInfo['email']);

      $em->persist($userToAdd);

      $em->flush();

        return $this->json($translator->trans('user-creation', ['user' => $userToAdd->getUsername()], 'messages'));
    }

    /**
     * @Route("/api/v1/admin/user/read", name="user-read", methods={"POST"})
     */
    public function read(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, TranslatorInterface $translator, PokemonService $pokemonService): Response
    {
      $userInfo = json_decode($request->getContent(), true);
      
      $user = $userRepository->findOneBy(['username' => $userInfo['username']]);       
      
      if($user == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'));
      }    

      foreach($user->getTeams() as $team){
        foreach ($team->getPokemon() as $pokemon){
          $pokemonService->calculateResistances($pokemon);
        }
      }

      if($encoder->isPasswordValid($user, $userInfo['password'])){

        return $this->json($user);
      }
      else{

        $userInfo['password'] = null;

        return $this->json($translator->trans('invalid-password', [], 'messages'));
      }

    }

    /**
     * @Route("/api/v1/admin/user/delete", name="user-delete", methods={"POST"})
     */
    public function delete(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, TranslatorInterface $translator, EntityManagerInterface $em)
    {
      $userInfo = json_decode($request->getContent(), true);

      $user = $userRepository->findOneBy(['username' => $userInfo['username']]);       
      
      if($user == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'));
      }

      if($encoder->isPasswordValid($user, $userInfo['password'])){

        $em->remove($user);

        $em->flush();

        return $this->json($translator->trans('user-deletion', ['user' => $user->getUsername()], 'messages'));
      }
      else{

        $userInfo['password'] = null;

        return $this->json($translator->trans('invalid-password', [], 'messages'));
      }
    }

    /**
     * @Route("/api/v1/admin/user/edit", name="user-edit", methods={"POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, TranslatorInterface $translator, UserRepository $userRepository): Response
    {

      $userInfo = json_decode($request->getContent(), true);

      $userToEdit = $userRepository->findOneBy(['username' => $userInfo['username']]);

      if($userToEdit == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'));
      }

      if($encoder->isPasswordValid($userToEdit, $userInfo['password'])){

        $userToEdit->setUsername($userInfo['username']);

        if(isset($userInfo['new_password']) && !is_null($userInfo['new_password'])){

          $userToEdit->setPassword($encoder->encodePassword($userToEdit, $userInfo['new_password']));
        }

        $newUserInfo['password'] = null;

        if(isset($userInfo['email']) && !is_null($userInfo['email'])){

          $userToEdit->setEmail($userInfo['email']);
        }

        $em->persist($userToEdit);

        $em->flush();
        
        return $this->json($translator->trans('user-edition', ['user' => $userToEdit->getUsername()], 'messages'));
      }
      else{

        $userInfo['password'] = null;

        return $this->json($translator->trans('invalid-password', [], 'messages'));
      }
      

        
    }
}
