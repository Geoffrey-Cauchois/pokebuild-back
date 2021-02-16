<?php

namespace App\Controller\Api\V1;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use App\Service\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1", name="api_v1_")
 */

class ApiController extends AbstractController
{
    /**
     * @Route("/pokemon", name="pokemon", methods={"GET"})
     */
    
    // route to send the list of all Pokemons

    public function pokemonList(PokemonRepository $pokemonRepository, PokemonService $pokemonService): Response
    {
        // we find all pokemons with the repository
        $pokemons = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances" to be able to display also the resistances 
        foreach($pokemons as $pokemon){

          $pokemonService->calculateResistances($pokemon);
        }

        // we send json with all Pokemons details + resistances
        return $this->json($pokemons);
    }

    /**
     * @Route("/pokemon/{id}", name="pokemon_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */

    // route to send the details of a pokemon by Its Id

    public function showPokemonById(Pokemon $pokemon, PokemonService $pokemonService): Response
    {

        // the paramConverter is findind the pokemon with the {id}, then we use the service "calculateResistances"
        $pokemonService->calculateResistances($pokemon);

        // we send json with the Pokemons details + resistances
        return $this->json($pokemon);
    }

    /**
     * @Route("/pokemon/{name}", name="pokemon_by_name", requirements={"name"="\w+"}, methods={"GET"})
     */

    // route to send the details of a pokemon by Its name

    public function showPokemonByName(PokemonRepository $pokemonRepository, $name, PokemonService $pokemonService): Response
    {
        // we find the pokemon by Its name with the repository
        $pokemonByName = $pokemonRepository->findOneBy(['name' => $name]);

        // we use the service "calculateResistances" to be able to display also the resistances 
        $pokemonService->calculateResistances($pokemonByName);

        // we send json with the Pokemons details + resistances
        return $this->json($pokemonByName);
    }

    /**
     * @Route("/pokemon/type/{name}", name="pokemon_by_type_name", requirements={"name"="\w+"}, methods={"GET"})
     */

    // route to send the list of pokemons by type

    public function showPokemonByType(PokemonRepository $pokemonRepository, $name, PokemonService $pokemonService): Response
    {
        // we find the pokemons by type with the repository (custom request)
        $pokemonsByType = $pokemonRepository->findByType($name);

        // for each pokemon we use the service "calculateResistances" to be able to display also the resistances 
        foreach($pokemonsByType as $pokemon){

            $pokemonService->calculateResistances($pokemon);
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonsByType);
        
    }

    /**
     * @Route("/pokemon/generation/{id}", name="pokemon_generation_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */

    // route to send the list of pokemons by Generation

    public function showPokemonByGeneration(PokemonRepository $pokemonRepository, $id, PokemonService $pokemonService): Response
    {
        // we find the pokemons by generation with the repository
        $pokemonsByGeneration = $pokemonRepository->findBy(['generation' => $id]);

        // for each pokemon we use the service "calculateResistances" to be able to display also the resistances 
        foreach($pokemonsByGeneration as $pokemon){

          $pokemonService->calculateResistances($pokemon);
        }  

        // we send json with Pokemons details + resistances
        return $this->json($pokemonsByGeneration);
    }
    
    /**
    * @Route("/pokemon/limit/{number}", name="pokemon_limit", requirements={"number"="\d+"}, methods={"GET"})
    */

    // route to send the 10, 50 or 400 first Pokemons

    public function showPokemonByLimit(PokemonRepository $pokemonRepository, $number, PokemonService $pokemonService): Response
    {
        // we find the first pokemons with the repository (custom request)
        $pokemonsByLimit = $pokemonRepository->findByLimit($number);

        // for each pokemon we use the service "calculateResistances" to be able to display also the resistances 
        foreach($pokemonsByLimit as $pokemon){

            $pokemonService->calculateResistances($pokemon);
        }  

        // we send json with Pokemons details + resistances
        return $this->json($pokemonsByLimit);
    }


    /**
     * @Route("/pokemon/types/{typeName1}/{typeName2}", name="pokemon_by_double_type", requirements={"typeName1"="\w+", "typeName2"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by their 2 types

    public function showPokemonByDoubleType(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName1, $typeName2): Response
    {
        // we find the pokemons by their 2 types with the repository (custom request)
        $pokemonByTypes = $pokemonRepository->findByTypes($typeName1, $typeName2);

        // for each pokemon we use the service "calculateResistances" to be able to display also the resistances
        foreach($pokemonByTypes as $pokemon){

            $pokemonService->calculateResistances($pokemon);
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByTypes);
    }

    /**
     * @Route("/pokemon/type/weakness/{typeName}", name="pokemon_by_weakness", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by weakness by type
    
    public function showPokemonByWeakness(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
        // we initialize an empty array
        $pokemonByWeakness = [];

        // we find all the pokemons
        $allPokemon = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances"
        // If for this type damage multiplier equals 2, we fill in the array with this pokemon
        foreach($allPokemon as $pokemon){

            $pokemonService->calculateResistances($pokemon);

            if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 2){

            array_push($pokemonByWeakness, $pokemon);

            }
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByWeakness);
    }

    /**
     * @Route("/pokemon/type/double-weakness/{typeName}", name="pokemon_by_double_weakness", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by doubale weakness by type

    public function showPokemonByDoubleWeakness(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
        // we initialize an empty array
        $pokemonByDoubleWeakness = [];

        // we find all the pokemons
        $allPokemon = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances"
        // If for this type damage multiplier equals 4, we fill in the array with this pokemon
        foreach($allPokemon as $pokemon){

            $pokemonService->calculateResistances($pokemon);

            if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 4){

            array_push($pokemonByDoubleWeakness, $pokemon);

            }
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByDoubleWeakness);
    }

    /**
     * @Route("/pokemon/type/resistance/{typeName}", name="pokemon_by_resistance", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by resistance by type

    public function showPokemonByResistance(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
        // we initialize an empty array
        $pokemonByResistance = [];

        // we find all the pokemons
        $allPokemon = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances"
        // If for this type damage multiplier equals 0.5, we fill in the array with this pokemon
        foreach($allPokemon as $pokemon){

            $pokemonService->calculateResistances($pokemon);

            if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0.5){

            array_push($pokemonByResistance, $pokemon);

            }
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByResistance);
    }

    /**
     * @Route("/pokemon/type/double-resistance/{typeName}", name="pokemon_by_double_resistance", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by double resistance by type

    public function showPokemonByDoubleResistance(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
        // we initialize an empty array
        $pokemonByDoubleResistance = [];

        // we find all the pokemons
        $allPokemon = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances"
        // If for this type damage multiplier equals 0.25, we fill in the array with this pokemon
        foreach($allPokemon as $pokemon){

            $pokemonService->calculateResistances($pokemon);

            if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0.25){

            array_push($pokemonByDoubleResistance, $pokemon);

            }
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByDoubleResistance);
    }

    /**
     * @Route("/pokemon/type/immunity/{typeName}", name="pokemon_by_immunity", requirements={"typeName"="\w+"}, methods={"GET"})
     *
     */

    // route to send the list of pokemons by immunity by type

    public function showPokemonByImmunity(PokemonRepository $pokemonRepository, PokemonService $pokemonService, $typeName): Response
    {
        // we initialize an empty array
        $pokemonByImmunity = [];

        // we find all the pokemons
        $allPokemon = $pokemonRepository->findAll();

        // for each pokemon we use the service "calculateResistances"
        // If for this type damage multiplier equals 0, we fill in the array with this pokemon
        foreach($allPokemon as $pokemon){

            $pokemonService->calculateResistances($pokemon);

            if($pokemon->getResistances()[ucfirst($typeName)]['damage_multiplier'] == 0){

            array_push($pokemonByImmunity, $pokemon);

            }
        }

        // we send json with Pokemons details + resistances
        return $this->json($pokemonByImmunity);
    }

    /**
     * @Route("/type", name="type", methods={"GET"})
     */
    
    // route to send the list of all TYpes
    public function typeList(TypeRepository $typeRepository): Response
    {
        // we find all types with the repository
        $types = $typeRepository->findAll();
        $typeNames = [];
        $typeImages = [];

        foreach($types as $type){

            $typeName = $type->getName();
            array_push($typeNames, $typeName);
            $typeImage = $type->getImage();
            array_push($typeImages, $typeImage);

        }
    
        // we send json with all Pokemons details + resistances
        return $this->json([
            $typeNames,
            $typeImages
        ]);
    }


}
