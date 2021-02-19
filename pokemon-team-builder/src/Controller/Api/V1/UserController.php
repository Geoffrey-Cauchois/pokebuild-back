<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user/creation", name="user-creation", methods={"POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {

      $newUserInfo = json_decode($request->getContent(), true);

      //dd($newUserInfo);

      $userToAdd = new User();

      $userToAdd->setUsername($newUserInfo['username']);

      $userToAdd->setPassword($encoder->encodePassword($userToAdd, $newUserInfo['password']));

      $newUserInfo['password'] = null;

      $userToAdd->setEmail($newUserInfo['email']);

      $em->persist($userToAdd);

      $em->flush();

        return $this->json('Utilisateur ' . $userToAdd->getUsername() . ' ajouté');
    }
}
