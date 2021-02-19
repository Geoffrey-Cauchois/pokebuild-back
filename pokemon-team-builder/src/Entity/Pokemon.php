<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 */
class Pokemon
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
     * @ORM\Column(type="string", length=500)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $sprite;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $HP;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $attack;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $defense;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $special_attack;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $special_defense;

    /**
     * @ORM\Column(type="integer")
     * @Ignore()
     */
    private $speed;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="pokemon")
     * @ORM\JoinColumn(nullable=false)
     * @Ignore()
     */
    private $generation;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="pokemon")
     * @Ignore()
     */
    private $types;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, inversedBy="pokemon")
     * @Ignore()
     */
    private $team;

    /**
     * @Ignore()
     */
    private $resistances;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->team = new ArrayCollection();
        $this->resistances = [];
    }

    public function __toString()
    {
      return $this->id . ' ' . $this->name . ' ';
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSprite(): ?string
    {
        return $this->sprite;
    }

    public function setSprite(string $sprite): self
    {
        $this->sprite = $sprite;

        return $this;
    }
    /**
     * @Ignore()
     */
    public function getHP(): ?int
    {
        return $this->HP;
    }

    public function setHP(int $HP): self
    {
        $this->HP = $HP;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * @Ignore()
     */
    public function getSpecialAttack(): ?int
    {
        return $this->special_attack;
    }

    public function setSpecialAttack(int $special_attack): self
    {
        $this->special_attack = $special_attack;

        return $this;
    }
    /**
     * @Ignore()
     */
    public function getSpecialDefense(): ?int
    {
        return $this->special_defense;
    }

    public function setSpecialDefense(int $special_defense): self
    {
        $this->special_defense = $special_defense;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     *
     * @return array
     */
    public function getStats(): array
    {
      $statsForApi = [
                      'HP' => $this->getHP(),
                      'attack' => $this->getAttack(),
                      'defense' => $this->getDefense(),
                      'special_attack' => $this->getSpecialAttack(),
                      'special_defense' => $this->getSpecialDefense(),
                      'speed' => $this->getSpeed()
                     ];

      return $statsForApi;
    }

    /**
     * @return array
     */
    public function getApiTypes(): array
    {
        // cette méthode permet d'éviter que le serializer ne tombe dans une référence circulaire
        $typesForApi = [];

        foreach($this->types as $type)
        {
            $typesForApi[] = ['name' => $type->getName(),
                              'image' => $type->getImage()];
        
        }
        return $typesForApi;
    }

    /**
     * @return int
     */
    public function getApiGeneration(): int
    {
        // cette méthode permet d'éviter que le serializer ne tombe dans une référence circulaire
        $generationForApi = $this->getGeneration()->getNumber();
        return $generationForApi;
    }

    /**
     * @return int
     */
    public function getApiResistances(): array
    {
        // cette méthode permet d'éviter que le serializer ne tombe dans une référence circulaire
        $resistancesForApi = [];
        foreach ($this->getResistances() as $type => $apiResistanceData){
          $resistancesForApi[] = $apiResistanceData;
        }
        return $resistancesForApi;
    }


    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addPokemon($this);
        }

        return $this;
    }


    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            $type->removePokemon($this);
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->team->contains($team)) {
            $this->team[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->team->removeElement($team);

        return $this;
    }

    /**
     * Get the value of resistances
     */ 
    public function getResistances()
    {
        return $this->resistances;
    }

    /**
     * Set the value of resistances
     *
     * @return  self
     */ 
    public function setResistances($resistances)
    {
        $this->resistances = $resistances;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
