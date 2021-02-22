<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;


/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, mappedBy="team")
     */
    private $pokemon;
    
    private $defensiveCover;

    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
        $this->defensiveCover = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Pokemon[]
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon[] = $pokemon;
            $pokemon->addTeam($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemon->removeElement($pokemon)) {
            $pokemon->removeTeam($this);
        }

        return $this;
    }

    /**
     * Get the value of defensiveCover
     * @Ignore()
     */ 
    public function getDefensiveCover()
    {
        return $this->defensiveCover;
    }
    /**
     * @return array
     * @Ignore()
     */
    public function getDefensiveCoverForApi()
    {
      $defensiveCoverForApi = [];

      foreach ($this->getDefensiveCover() as $type => $defensiveCoverData){
        $typeInfo = ['name' => $type];
        $defensiveCoverData = array_merge($typeInfo, $defensiveCoverData);
        $defensiveCoverForApi[] = $defensiveCoverData;
      }

      return $defensiveCoverForApi;
    }

    /**
     * Set the value of defensiveCover
     *
     * @return  self
     */ 
    public function setDefensiveCover($defensiveCover)
    {
        $this->defensiveCover = $defensiveCover;

        return $this;
    }
}
