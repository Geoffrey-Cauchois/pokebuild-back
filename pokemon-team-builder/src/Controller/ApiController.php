<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api", methods={"GET"})
     */
    public function list(): Response
    {
        $pokemons = [];

       
        $generation1 = new Generation;
        $generation1->setNumber(1);
        $generation2 = new Generation;
        $generation2->setNUmber(2);

        $type1 = new Type;
        $type1->setName('pierre');
        $type2 = new Type;
        $type2->setName('feu');
        $type3 = new Type;
        $type3->setName('eau');
        $type4 = new Type;
        $type4->setName('électrique');
        $type5 = new Type;
        $type5->setName('volant');

        $pokemon1 = new Pokemon;
        $pokemon1->setName('bulbizarre');
        $pokemon1->setImage('bulbizarre.png');
        $pokemon1->setSprite('bulbizarreSprite.png');
        $pokemon1->setHP(4);
        $pokemon1->setAttack(2);
        $pokemon1->setDefense(1);
        $pokemon1->setSpecialAttack(3);
        $pokemon1->setSpecialDefense(3);
        $pokemon1->setSpeed(2);
        $pokemon1->setGeneration($generation1);
        $pokemon1->addType($type1);
        $pokemon1->addType($type3);

        $pokemon2 = new Pokemon;
        $pokemon2->setName('florizarre');
        $pokemon2->setImage('florizarre.png');
        $pokemon2->setSprite('florizarreSprite.png');
        $pokemon2->setHP(1);
        $pokemon2->setAttack(3);
        $pokemon2->setDefense(2);
        $pokemon2->setSpecialAttack(5);
        $pokemon2->setSpecialDefense(4);
        $pokemon2->setSpeed(3);
        $pokemon2->setGeneration($generation1);
        $pokemon2->addType($type2);
    

        $pokemon3 = new Pokemon;
        $pokemon3->setName('pikachu');
        $pokemon3->setImage('pikachu.png');
        $pokemon3->setSprite('pikachuSprite.png');
        $pokemon3->setHP(2);
        $pokemon3->setAttack(5);
        $pokemon3->setDefense(3);
        $pokemon3->setSpecialAttack(5);
        $pokemon3->setSpecialDefense(5);
        $pokemon3->setSpeed(4);
        $pokemon3->setGeneration($generation2);
        $pokemon3->addType($type2);
        $pokemon3->addType($type4);

        $pokemon4 = new Pokemon;
        $pokemon4->setName('miaouss');
        $pokemon4->setImage('miaouss.png');
        $pokemon4->setSprite('miaoussSprite.png');
        $pokemon4->setHP(1);
        $pokemon4->setAttack(3);
        $pokemon4->setDefense(2);
        $pokemon4->setSpecialAttack(4);
        $pokemon4->setSpecialDefense(2);
        $pokemon4->setSpeed(1);
        $pokemon4->setGeneration($generation2);
        $pokemon4->addType($type5);


        array_push($pokemons, $pokemon1, $pokemon2, $pokemon3, $pokemon4);

        return $this->json($pokemons);
    }
}
