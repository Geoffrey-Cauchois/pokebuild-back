<?php 

namespace App\Service;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger 
{

    private $em;
    private $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $symfonySlugger)
    {
        $this->em = $em;
        $this->slugger = $symfonySlugger;
    }

    public function sluggify($string)
    {
        return $this->slugger->slug($string);
    }

    public function sluggifyPokemon(Pokemon $pokemon)
    {

        $sluggedName = $this->sluggify($pokemon->getName());

        $pokemon->setSlug($sluggedName);

        return $pokemon;
        
    }
}