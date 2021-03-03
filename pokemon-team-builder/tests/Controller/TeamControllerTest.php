<?php

namespace App\Tests\Controller;

use App\Repository\ApiUserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamControllerTest extends WebTestCase
{


    private $jwtManager;
    private $apiUserRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->jwtManager = self::$container->get(JWTTokenManagerInterface::class);
        $this->apiUserRepository = self::$container->get(ApiUserRepository::class);


    }
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'pokebuild', $password = 'chenipan')
    {

        $this->client->request(
        'POST',
        '/api/v1/login_check',
        array(),
        array(),
        array('CONTENT_TYPE' => 'application/json'),
        json_encode(array(
            '_username' => $username,
            '_password' => $password,
            ))
        );
        $request = new Request();
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $token = $this->jwtManager->create($this->apiUserRepository->findOneBy(['username' => 'pokebuild']));
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));

        return $this->client;

    }

    /**
    * test getPagesAction
    */
    public function testTeamName()
    {

        $client = $this->createAuthenticatedClient();
        $client->request('GET', 'http://localhost/api/v1/admin/team/show/quia');

        $this->assertResponseIsSuccessful();
    
    }

    public function testTeamId()
    {

        $client = $this->createAuthenticatedClient();
        $client->request('GET', 'http://localhost/api/v1/admin/team/show/1');

        $this->assertResponseIsSuccessful();
    
    }

    
}
