<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\ResistanceModifyingAbility;
use App\Entity\Type;
use App\Repository\GenerationRepository;
use App\Repository\PokemonRepository;
use App\Repository\ResistanceModifyingAbilitiesRepository;
use App\Repository\TypeRepository;
use App\Service\PokemonService;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{   
    /**
     * @Route("/test/res", name="test-res")
     */
    public function testResistances(PokemonRepository $pokemonRepository, PokemonService $service, Request $request, ResistanceModifyingAbilitiesRepository $resistanceModifyingAbilitiesRepository)
    {
      if($request->server->get('APP_ENV') == 'prod'){

        throw $this->createNotFoundException('cette route de l\'api n\'existe pas');
      }

      //dump($request->getLocale());

      $pokemon = $pokemonRepository->find(rand(1, 898));

      $ability = $resistanceModifyingAbilitiesRepository->findOneBy(['name' => 'Absorbe-Eau']);

      $service->calculateResistances($pokemon, $ability);

      return $this->json($pokemon);

      return $this->render('base.html.twig');
    }

    /**
     * @Route("/api/v1/admin", name="test-api")
     */
    public function apiTest(Request $request)
    {
      if($request->server->get('APP_ENV') == 'prod'){

        throw $this->createNotFoundException('cette route de l\'api n\'existe pas');
      }

      return $this->json('ok');
    }

    /**
     * @Route("/test/{id}", name="test", requirements={"id"="\d+"})
     */
    /* public function fillDatabasePokemon(EntityManagerInterface $em, GenerationRepository $generationRepository, TypeRepository $typeRepository, PokemonRepository $pokemonRepository, $id, Request $request): Response
    {
      if($request->server->get('APP_ENV') == 'prod'){

        throw $this->createNotFoundException('cette route de l\'api n\'existe pas');
      }

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

          $typeToAdd->setName($name);

          $englishName = $decodedTypeData->name;

          $typeToAdd->setEnglishName($englishName);

          $image = $typesImages[$i -1];

          $typeToAdd->setImage($image);

          $em->persist($typeToAdd);



          //dump($decodedTypeData);
        }

        $em->flush();

        $types = $typeRepository->findAll();

        //dump($types);

        foreach ($types as $type){
          
          $typeData = file_get_contents($apiTypesUrl . $type->getEnglishName());

          $decodedTypeData = json_decode($typeData);
          
          $vulnerabilities = $decodedTypeData->damage_relations->double_damage_from;

          foreach ($vulnerabilities as $vulnerabilityData){

            $vulnerabilityTypeData = file_get_contents($apiTypesUrl . $vulnerabilityData->name);

            $decodedVulnerabilityTypeData = json_decode($vulnerabilityTypeData);

            $typeEnglishName = $decodedVulnerabilityTypeData->name;

            $vulnerability = $typeRepository->findOneBy(['english_name' => $typeEnglishName]);

            $type->addVulnerableTo($vulnerability);
          }

          $resistances = $decodedTypeData->damage_relations->half_damage_from;

          foreach ($resistances as $resistanceData){

            $resistanceTypeData = file_get_contents($apiTypesUrl . $resistanceData->name);

            $decodedResistanceTypeData = json_decode($resistanceTypeData);

            $typeEnglishName = $decodedResistanceTypeData->name;

            $resistance = $typeRepository->findOneBy(['english_name' => $typeEnglishName]);

            $type->addResistantTo($resistance);
          }

          $immunities = $decodedTypeData->damage_relations->no_damage_from;

          foreach ($immunities as $immunityData){

            $immunityTypeData = file_get_contents($apiTypesUrl . $immunityData->name);

            $decodedImmunityTypeData = json_decode($immunityTypeData);

            $typeEnglishName = $decodedImmunityTypeData->name;

            $immunity = $typeRepository->findOneBy(['english_name' => $typeEnglishName]);

            $type->addImmuneTo($immunity);
          }
          
        }

        $em->flush();

        $types = $typeRepository->findAll();

        foreach($types as $type){

          

            $vulnerabilities = [];

            $vulnerablesTypes = $type->getVulnerableTo();

            foreach($vulnerablesTypes as $vulnerablesType){

              array_push($vulnerabilities, $vulnerablesType);

            }

            $resistances = [];

            $resistantTypes = $type->getResistantTo();

            foreach($resistantTypes as $resistantType){

              array_push($resistances, $resistantType);

            }

            $immunities = [];

            $immunityTypes = $type->getImmuneTo();

            foreach($immunityTypes as $immunityType){

              array_push($immunities, $immunityType);

            }
          foreach($types as $testedType){

            if (!in_array($testedType, $vulnerabilities) && !in_array($testedType, $resistances) && !in_array($testedType, $immunities)){

              $type->addNeutralTo($testedType);

            }
          }
        }

        $em->flush();

      }

      if ($generationRepository->findOneBy(['number' => 1]) == null ){

        for($i = 1; $i <= 8; $i ++){

          $generationToAdd = new Generation;

          $generationData = file_get_contents($apiGenerationUrl . $i);

          $decodedGenerationData = json_decode($generationData);

          $generationNumber = $decodedGenerationData->id;

          $generationToAdd->setNumber($generationNumber);

          $em->persist($generationToAdd);
        }
      

        $em->flush();
      }

      if($id > 10000){

      
        for ($i = $id; $i < $id +55; $i ++){

          $pokemonToAdd = new Pokemon;
          
          $pokemonData = file_get_contents($apiUrl . $i);
          
          $decodedPokemonData = json_decode($pokemonData);
          
          //dd($decodedPokemonData);
          
          $name = $decodedPokemonData->name;

          //dd($decodedPokemonData);

          if(preg_match('~-mega~', $name) == 0){
            $pokemonToAdd->setName($name);

            $pokemonDataFr = file_get_contents($decodedPokemonData->species->url);

            $decodedPokemonDataFr = json_decode($pokemonDataFr);

            $pokedexId = $decodedPokemonDataFr->id;

            $pokemonToAdd->setPokedexId($pokedexId);

            $generationId = substr($decodedPokemonDataFr->generation->url, -2, 1);

            $generation = $generationRepository->findOneBy(['number' => $generationId]);

            $pokemonToAdd->setGeneration($generation);

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

            $types = $decodedPokemonData->types;

            foreach ($types as $type){

              $typeName = $type->type->name;

              $typeToAttribute = $typeRepository->findOneBy(['english_name' => $typeName]);

              $pokemonToAdd->addType($typeToAttribute);
            }

            $em->persist($pokemonToAdd);
          }

          
        }

        $em->flush();
      }

        return $this->json('test terminé');
    } */    
    
    /**
     * @Route("/test", name="test-json")
     */
    public function test(PokemonRepository $pokemonRepository, PokemonService $service, Request $request){

      if($request->server->get('APP_ENV') == 'prod'){

        throw $this->createNotFoundException('cette route de l\'api n\'existe pas');
      }
      
      $test = $pokemonRepository->find(rand(1, 898));

      $service->calculateResistances($test);
      
      $types = $test->getTypes();

      //dump($types);

      return $this->json($test);
    }

    /**
     * @Route("/test/ab", name="test-ab")
     */
    public function testAbility(Request $request, EntityManagerInterface $em, TypeRepository $typeRepository, PokemonRepository $pokemonRepository): Response
    {
      if($request->server->get('APP_ENV') == 'prod'){

        throw $this->createNotFoundException('cette route de l\'api n\'existe pas');
      }

      $abilitiesData = ['water-absorb' => ['modifiedTypes' => ['Eau'],
                                           'multiplier' => 0],
                        'volt-absorb' => ['modifiedTypes' => ['Électrik'],
                                          'multiplier' => 0],
                        'levitate' => ['modifiedTypes' => ['Sol'],
                                       'multiplier' => 0],
                        'dry-skin' => ['modifiedTypes' => ['Eau'],
                                       'multiplier' => 0],
                        'flash-fire' => ['modifiedTypes' => ['Feu'],
                                         'multiplier' => 0],
                        'heatproof' => ['modifiedTypes' => ['Feu'],
                                        'multiplier' => 0.5],
                        'motor-drive' => ['modifiedTypes' => ['Électrik'],
                                          'multiplier' => 0],
                        'thick-fat' => ['modifiedTypes' => ['Feu', 'Glace'],
                                       'multiplier' => 0.5],
                        'fluffy' => ['modifiedTypes' => ['Feu'],
                                     'multiplier' => 2],
                        'sap-sipper' => ['modifiedTypes' => ['Plante'],
                                         'multiplier' => 0],
                        'wonder-guard' => ['modifiedTypes' => [],
                                           'multiplier' => 0],
                        'storm-drain' => ['modifiedTypes' => ['Eau'],
                                           'multiplier' => 0],
                        'lightning-rod' => ['modifiedTypes' => ['Électrik'],
                                            'multiplier' => 0]
                       ];
      
      $apiAbilitiesUrl = 'https://pokeapi.co/api/v2/ability/';

      foreach ($abilitiesData as $name => $abilityInfo){

        $ability = new ResistanceModifyingAbility;

        $abilityData = file_get_contents($apiAbilitiesUrl . $name);

        $decodedAbilityData = json_decode($abilityData);

        $frName = $decodedAbilityData->names[3]->name;

        $ability->setName($frName);

        $modifiedTypes = $abilityInfo['modifiedTypes'];

        foreach ($modifiedTypes as $typeName){
          
          $associatedType = $typeRepository->findOneBy(['name' => $typeName]);

          $ability->addModifiedType($associatedType);
        }

        $multiplier = $abilityInfo['multiplier'];

        $ability->setMultiplier($multiplier);

        foreach($decodedAbilityData->pokemon as $pokemonData){

          preg_match('~/\d+~', $pokemonData->pokemon->url, $matches);

          $pokemonId = substr($matches[0], 1);

          if($pokemonId <= 898){

            $pokemonThatCanHaveTheAbility = $pokemonRepository->find($pokemonId);

            $ability->addPokemon($pokemonThatCanHaveTheAbility);
          }
        }
        $em->persist($ability);
      }

      $em->flush();

      return $this->render('base.html.twig');
    }
}

