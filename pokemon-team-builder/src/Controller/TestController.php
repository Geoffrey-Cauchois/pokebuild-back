<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\Type;
use App\Repository\GenerationRepository;
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
    public function index(EntityManagerInterface $em, GenerationRepository $generationRepository, TypeRepository $typeRepository): Response
    {

      $apiUrl = 'https://pokeapi.co/api/v2/pokemon/';

      $apiUrlFr = 'https://pokeapi.co/api/v2/pokemon-species/';

      $apiGenerationUrl = 'https://pokeapi.co/api/v2/generation/';

      $apiTypesUrl = 'https://pokeapi.co/api/v2/type/';

      $typesImages = [
                      'https://cdn.pixabay.com/photo/2018/05/20/21/00/pokemon-3416764_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/01/40/pokemon-3414806_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/01/40/pokemon-3414808_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/21/00/pokemon-3416765_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/21/00/pokemon-3416762_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/21/13/06/pokemon-3418255_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/18/15/43/pokemon-3411386_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/01/40/pokemon-3414809_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/21/13/06/pokemon-3418256_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/01/40/pokemon-3414807_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/21/13/06/pokemon-3418257_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/01/41/pokemon-3414810_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/18/15/43/pokemon-3411389_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/21/01/pokemon-3416767_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/20/21/00/pokemon-3416763_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/18/15/43/pokemon-3411388_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/18/15/43/pokemon-3411387_1280.png',
                      'https://cdn.pixabay.com/photo/2018/05/18/15/43/pokemon-3411390_1280.png'
                    ];

      if ($typeRepository->findOneBy(['name' => 'Normal']) == null){

        for($i = 1; $i <= 18; $i ++){

          $typeToAdd = new Type;

          $typeData = file_get_contents($apiTypesUrl . $i);

          $decodedTypeData = json_decode($typeData);

          $name = $decodedTypeData->names[2]->name;

          dump($name);
        }
      }

      if ($generationRepository->findOneBy(['number' => 1]) == null ){

        for($i = 1; $i <= 7; $i ++){

          $generationToAdd = new Generation;

          $generationData = file_get_contents($apiGenerationUrl . $i);

          $decodedGenerationData = json_decode($generationData);

          $generationNumber = $decodedGenerationData->id;

          $generationToAdd->setNumber($generationNumber);

          $em->persist($generationToAdd);
        }
      

        $em->flush();
      }

      for ($i = 1; $i <= 3; $i ++){

        $pokemonToAdd = new Pokemon;
        
        $pokemonDataFr = file_get_contents($apiUrlFr . $i);

        $decodedPokemonDataFr = json_decode($pokemonDataFr);

        dump($decodedPokemonDataFr);

        $nameFr = $decodedPokemonDataFr->names[4]->name;

        $pokemonToAdd->setName($nameFr);

        $generationId = substr($decodedPokemonDataFr->generation->url, -2, 1);

        $generation = $generationRepository->findOneBy(['number' => $generationId]);

        $pokemonToAdd->setGeneration($generation);

        $pokemonData = file_get_contents($apiUrl . $i);

        $decodedPokemonData = json_decode($pokemonData);

        dump($decodedPokemonData);

        $images = $decodedPokemonData->sprites->other;
        
        $j = 0;
        foreach ($images as $imagedata){

          if($j = 1){
            $imageUrl = $imagedata->front_default;
          }

          $j += 1;
        }

        $pokemonToAdd->setImage($imageUrl);

        $spriteUrl = $decodedPokemonData->sprites->front_default;

        $pokemonToAdd->setSprite($spriteUrl);

        $HP = $decodedPokemonData->stats[0]->base_stat;

        $pokemonToAdd->setHP($HP);

        $attack = $decodedPokemonData->stats[1]->base_stat;

        $pokemonToAdd->setAttack($attack);

        $defense = $decodedPokemonData->stats[2]->base_stat;

        $pokemonToAdd->setDefense($defense);

        $special_attack = $decodedPokemonData->stats[3]->base_stat;

        $pokemonToAdd->setSpecialAttack($special_attack);

        $special_defense = $decodedPokemonData->stats[4]->base_stat;

        $pokemonToAdd->setSpecialDefense($special_defense);

        $speed = $decodedPokemonData->stats[5]->base_stat;

        $pokemonToAdd->setSpeed($speed);
      }

        return $this->render('base.html.twig');
    }
}
