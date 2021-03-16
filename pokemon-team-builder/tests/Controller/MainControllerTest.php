<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{

    public function testRedirectHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseRedirects('/api/v1');
        
    }

    public function testHome()
    {
      $client = static::createClient();
      $crawler = $client->request('GET', '/api/v1');

      $this->assertSelectorTextContains('div.card:nth-child(11) > div:nth-child(2) > p:nth-child(2) > strong:nth-child(1)', 'double-weakness');
    } 
}



