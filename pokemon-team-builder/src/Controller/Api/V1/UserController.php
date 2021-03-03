<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\ApiUserRepository;
use App\Repository\PokemonRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\PokemonService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user/create", name="user-create", methods={"POST"})
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder, ApiUserRepository $apiUserRepository, TranslatorInterface $translator, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $em, MailerInterface $mailer, CsrfTokenManagerInterface $csrf, ValidatorInterface $validator): Response
    {

      $newUserInfo = json_decode($request->getContent(), true);

      $userToAdd = new User();

      $userToAdd->setIsActive(false);

      $userToAdd->setUsername($newUserInfo['username']);

      if(isset($newUserInfo['passwordConfirm']) && isset($newUserInfo['password']) && $newUserInfo['passwordConfirm'] == $newUserInfo['password']){

        $violations =    $validator->validate($newUserInfo['password'], new Regex([
          'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}/m'
           ]));

          if(!empty(strval($violations))){

            return $this->json($translator->trans('invalid-password', [], 'messages'), 400);
          }

        $userToAdd->setPassword($encoder->encodePassword($userToAdd, $newUserInfo['password']));
        
        $newUserInfo['password'] = null;

        $newUserInfo['passwordConfirm'] = null;
      }
      else{

        $newUserInfo['password'] = null;

        $newUserInfo['passwordConfirm'] = null;

        return $this->json($translator->trans('non-matching-passwords', [], 'messages'), 400);
      }
      

      

      if(filter_var($newUserInfo['email'], FILTER_VALIDATE_EMAIL) == false){

        return $this->json($translator->trans('wrong-email', [], 'messages'), 400);
      }
      elseif(preg_match('~@yopmail~', $newUserInfo['email']) != false){

        return $this->json($translator->trans('wrong-email', [], 'messages'), 400);
      }
      else{

        $userToAdd->setEmail($newUserInfo['email']);
      }

      $csrfToken = $csrf->getToken('token');

      $userToAdd->setValidationToken($csrfToken);

      $em->persist($userToAdd);

      try {
          $em->flush();
          }
      catch (UniqueConstraintViolationException $e) {
        return $this->json($translator->trans('existing-user', [], 'messages'), 400);
      }

      $token = $jwtManager->create($apiUserRepository->findOneBy(['username' => $request->server->get('TOKEN_USER')]));
      
      

      $email = (new TemplatedEmail())
        ->from('pokebuild.noreply@gmail.com')
        ->to($userToAdd->getEmail())
        ->subject($translator->trans('signup', [], 'emails') . ' !')
        ->htmlTemplate('emails/signup.html.twig')
        ->context([
                  'username' => $userToAdd->getUsername(),
                  'signup' => $translator->trans('signup', [], 'emails'),
                  'validationMessage' => $translator->trans('validation-link', [], 'emails'),
                  'noreplyMessage' => $translator->trans('mail-auto-message', [], 'emails'),
                  'token' => $csrfToken
                  ]);

      $mailer->send($email);

      

      $return = [
                  'message' => $translator->trans('user-creation', ['user' => $userToAdd->getUsername()], 'messages'),
                  'username' => $userToAdd->getUsername(),
                  'token' => $token
                ];

        return $this->json($return);
    }

    /**
     * @Route("/api/v1/admin/user/read", name="user-read", methods={"POST"})
     */
    public function read(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, TranslatorInterface $translator, PokemonService $pokemonService): Response
    {
      $userInfo = json_decode($request->getContent(), true);
      
      $user = $userRepository->findOneBy(['username' => $userInfo['username']]);       
      
      if($user == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'), 400);
      }    

      foreach($user->getTeams() as $team){
        foreach ($team->getPokemon() as $pokemon){
          $pokemonService->calculateResistances($pokemon);
        }
      }


        return $this->json($user);

    }

    /**
     * @Route("/api/v1/admin/user/delete", name="user-delete", methods={"POST"})
     */
    public function delete(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, TranslatorInterface $translator, EntityManagerInterface $em)
    {
      $userInfo = json_decode($request->getContent(), true);

      $user = $userRepository->findOneBy(['username' => $userInfo['username']]);       
      
      if($user == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'), 400);
      }

      if($encoder->isPasswordValid($user, $userInfo['password']) == false){

        return $this->json($translator->trans('invalid-password', [], 'messages'), 400);
      }

        $teams = $user->getTeams();

        foreach($teams as $team){

          $em->remove($team);
        }

        $em->remove($user);

        $em->flush();

        return $this->json($translator->trans('user-deletion', ['user' => $user->getUsername()], 'messages'));
    
    }

    /**
     * @Route("/api/v1/admin/user/edit", name="user-edit", methods={"POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, TranslatorInterface $translator, UserRepository $userRepository, ValidatorInterface $validator, MailerInterface $mailer): Response
    {

      $userInfo = json_decode($request->getContent(), true);

      $userToEdit = $userRepository->findOneBy(['username' => $userInfo['username']]);

      if($userToEdit == null){

        return $this->json($translator->trans('wrong-username', [], 'messages'), 400);
      }

      if($encoder->isPasswordValid($userToEdit, $userInfo['password']) == false){

        return $this->json($translator->trans('invalid-password', [], 'messages'), 400);
      }

      $modifiedPassword = false;

      if(isset($userInfo['newPasswordConfirm']) && $userInfo['newPassword'] && $userInfo['newPasswordConfirm'] == $userInfo['newPassword']){

        
        $violations =    $validator->validate($userInfo['newPassword'], new Regex([
                         'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}/m'
                          ]));


        if(!empty(strval($violations))){

          return $this->json($translator->trans('invalid-password', [], 'messages'), 418);
        }

        $userToEdit->setPassword($encoder->encodePassword($userToEdit, $userInfo['newPassword']));
          
        $userInfo['newPassword'] = null;
        $userInfo['newPasswordConfirm'] = null;

        $modifiedPassword = true;
      }

        

      if(isset($userInfo['email']) && !is_null($userInfo['email'])){

        if(filter_var($userInfo['email'], FILTER_VALIDATE_EMAIL) == false){

          return $this->json($translator->trans('wrong-email', [], 'messages'), 400);
        }
        elseif(preg_match('~@yopmail~', $userInfo['email']) != false){
  
          return $this->json($translator->trans('wrong-email', [], 'messages'), 400);
        }

        $userToEdit->setEmail($userInfo['email']);

        $email = (new TemplatedEmail())
        ->from('pokebuild.noreply@gmail.com')
        ->to($userInfo['email'])
        ->subject($translator->trans('new-email', [], 'emails') . ' !')
        ->htmlTemplate('emails/new.html.twig')
        ->context([
                  'username' => $userToEdit->getUsername(),
                  'notice' => $translator->trans('new-eamil-notice', [], 'emails'),
                  'greetings' => $translator->trans('greetings', [], 'messages'),
                  'noreplyMessage' => $translator->trans('mail-auto-message', [], 'emails'),
                  'redirectionSite' => $translator->trans('redirection-site', [], 'emails'),
                  'redirectionApi' => $translator->trans('redirection-api', [], 'emails'),
                  ]);

        $mailer->send($email);
      }

        $em->persist($userToEdit);

        $em->flush();

        if(!isset($userInfo['email']) && $modifiedPassword == false){

          return $this->json($translator->trans('user-edition-null', ['user' => $userToEdit->getUsername()], 'messages'));          
        }
        else{

          return $this->json($translator->trans('user-edition', ['user' => $userToEdit->getUsername()], 'messages'));
        }
        
        
        
    }

    /**
     * @Route("/api/v1/user/login", name="user-login", methods={"POST"})
     */
    public function login(JWTTokenManagerInterface $jwtManager, Request $request, TranslatorInterface $translator, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, ApiUserRepository $apiUserRepository)
    {
      {
        $userInfo = json_decode($request->getContent(), true);
  
        $user = $userRepository->findOneBy(['username' => $userInfo['username']]);       
        
        if($user == null){
  
          return $this->json($translator->trans('wrong-username', [], 'messages'), 400);
        }

        if($user->getIsActive() == false){

          return $this->json($translator->trans('inactive-user', [], 'emails'), 400);
        }
  
        if($encoder->isPasswordValid($user, $userInfo['password'])){
  
          $token = $jwtManager->create($apiUserRepository->findOneBy(['username' => $request->server->get('TOKEN_USER')]));
  
          return $this->json([
                              'message' => $translator->trans('login', ['user' => $user->getUsername()], 'messages'),
                              'username' => $user->getUsername(),
                              'token' => $token
                             ]);
        }
        else{
  
          $userInfo['password'] = null;
  
          return $this->json($translator->trans('invalid-password', [], 'messages'), 400);
        }
      }
    }

    /**
     * @Route("user/validate/{user}/", name="user-validate", requirements={"user"="\w+"}, methods={"GET"})
     */
    public function validateUser(UserRepository  $userRepository,Request $request,EntityManagerInterface $em, SessionInterface $session, TranslatorInterface $translator, $user): Response
    {
      $userToValidate = $userRepository->findOneBy(['username' => $user]);

      if($userToValidate == null){

        throw new Exception('user does not exist');
      }

      $token = $request->query->get('token');

      if ($userToValidate->getIsActive() == true){

        throw new Exception('user account is already activated');
      }
      else{

        $tokenToVerify = $userToValidate->getValidationToken();
      }

      if($tokenToVerify === $token && !is_null($token) && !is_null($tokenToVerify)){

        $userToValidate->setIsActive(true);

        $userToValidate->setValidationToken(null);
        
        $em->flush();
      }
      else{

        throw new Exception('invalid token');
      }

      return $this->render('emails/confirmation.html.twig', [
                                                            'redirectionSite' => $translator->trans('redirection-site', [], 'emails'),
                                                            'redirectionApi' => $translator->trans('redirection-api', [], 'emails'),
                                                            'validationSuccess' => $translator->trans('validation-success', [], 'emails'),
                                                            ]);
    }
}
