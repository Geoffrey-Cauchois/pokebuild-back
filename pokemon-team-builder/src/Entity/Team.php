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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams", fetch="EAGER")
     * @Ignore()
     */
    private $user;
    
    private $defensiveCover;

    /**
     * @ORM\OneToMany(targetEntity=TeamAppartenance::class, mappedBy="team", orphanRemoval=true)
     * @Ignore()
     */
    private $teamAppartenances;

    /**
     * @Ignore()
     */
    private $pokemon;

    public function __construct()
    {
        $this->defensiveCover = [];
        $this->teamAppartenances = new ArrayCollection();
        $this->pokemon = [];
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

     /**
     * @return string
     */
    public function getApiUser(): string
    {
        // cette méthode permet d'éviter que le serializer ne tombe dans une référence circulaire
        $userForApi = $this->getUser()->getUsername();
        return $userForApi;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    /**
     * @return Collection|TeamAppartenance[]
     */
    public function getTeamAppartenances(): Collection
    {
        return $this->teamAppartenances;
    }

    public function addTeamAppartenance(TeamAppartenance $teamAppartenance): self
    {
        if (!$this->teamAppartenances->contains($teamAppartenance)) {
            $this->teamAppartenances[] = $teamAppartenance;
            $teamAppartenance->setTeam($this);
        }

        return $this;
    }

    public function removeTeamAppartenance(TeamAppartenance $teamAppartenance): self
    {
        if ($this->teamAppartenances->removeElement($teamAppartenance)) {
            // set the owning side to null (unless already changed)
            if ($teamAppartenance->getTeam() === $this) {
                $teamAppartenance->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of pokemon
     */ 
    public function getPokemon()
    {
        return $this->pokemon;
    }

    /**
     * Set the value of pokemon
     *
     * @return  self
     */ 
    public function setPokemon($pokemon)
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function addPokemon(Pokemon $pokemonToAdd)
    {
        $this->pokemon[] = $pokemonToAdd;
    }
}
