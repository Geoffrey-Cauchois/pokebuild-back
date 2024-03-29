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
     * @ORM\Column(type="integer")
     */
    private $pokedexId;

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
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="pokemon", fetch="EAGER")
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
     * @Ignore()
     */
    private $resistances;
    
    /**
     * @Ignore
     */
    private $resistancesWithAbilities;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=TeamAppartenance::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $teamAppartenances;

    /**
     * @ORM\ManyToMany(targetEntity=ResistanceModifyingAbility::class, mappedBy="pokemon")
     * @Ignore()
     */
    private $resistanceModifyingAbility;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="additionalForms", fetch="EAGER")
     * @Ignore()
     */
    private $originalForm;

    /**
     * @ORM\OneToMany(targetEntity=Pokemon::class, mappedBy="originalForm")
     * @Ignore()
     */
    private $additionalForms;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="evolutions", fetch="EAGER")
     * @Ignore()
     */
    private $evolvedFrom;

    /**
     * @ORM\OneToMany(targetEntity=Pokemon::class, mappedBy="evolvedFrom")
     * @Ignore()
     */
    private $evolutions;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->team = new ArrayCollection();
        $this->resistances = [];
        $this->teamAppartenances = new ArrayCollection();
        $this->resistanceModifyingAbility = new ArrayCollection();
        $this->additionalForms = new ArrayCollection();
        $this->evolutions = new ArrayCollection();
        $this->resistancesWithAbilities = [];
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
     * @return array
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
    /**
     * @return array
     */
    public function getResistanceModifyingAbilitiesForApi()
    {
      // cette méthode permet d'éviter que le serializer ne tombe dans une référence circulaire
      $resistanceModifyingAbilitiesForApi = [];
      foreach ($this->getResistanceModifyingAbility() as $ability){
        $resistanceModifyingAbilitiesForApi['name'] = $ability->getName();
        $resistanceModifyingAbilitiesForApi['slug'] = $ability->getSlug();
      }
      return $resistanceModifyingAbilitiesForApi;
    }

    /**
     * @return str|array
     */
    public function getApiEvolutions()
    {
      

      if(!is_null($this->getEvolutions())){

        $evolutionsForApi = [];
        foreach($this->getEvolutions() as $evolution){
          
          $evolutionData = [];
          $evolutionData['name'] = $evolution->getName();
          $evolutionData['pokedexId'] = $evolution->getPokedexId();
          array_push($evolutionsForApi, $evolutionData);
        }
      }
      else{
         $evolutionsForApi = 'none';
      }

      return $evolutionsForApi;
    }

    /**
     * @return void
     */
    public function getApiPreEvolution()
    {
      if(!is_null($this->getEvolvedFrom())){
        $preEvolutionForApi = [];
        $preEvolutionForApi['name'] = $this->getEvolvedFrom()->getName();
        $preEvolutionForApi['pokedexIdd'] = $this->getEvolvedFrom()->getPokedexId();
      }
      else{
        $preEvolutionForApi = 'none';
      }

      return $preEvolutionForApi;
    }

    /**
     * @return array
     */
    public function getApiResistancesWithAbilities(): array
    {
      $resistanceWithAbilityForApi = [];

      if(!is_null($this->getResistancesWithAbilities())){

        foreach($this->getResistancesWithAbilities() as $resistanceData){

          $resistanceWithAbilityForApi[] = $resistanceData;
        }
      }

      return $resistanceWithAbilityForApi;
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

    /**
     * @return Collection|TeamAppartenance[]
     * @Ignore()
     */
    public function getTeamAppartenances(): Collection
    {
        return $this->teamAppartenances;
    }

    public function addTeamAppartenance(TeamAppartenance $teamAppartenance): self
    {
        if (!$this->teamAppartenances->contains($teamAppartenance)) {
            $this->teamAppartenances[] = $teamAppartenance;
            $teamAppartenance->setPokemon($this);
        }

        return $this;
    }

    public function removeTeamAppartenance(TeamAppartenance $teamAppartenance): self
    {
        if ($this->teamAppartenances->removeElement($teamAppartenance)) {
            // set the owning side to null (unless already changed)
            if ($teamAppartenance->getPokemon() === $this) {
                $teamAppartenance->setPokemon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResistanceModifyingAbility[]
     */
    public function getResistanceModifyingAbility(): Collection
    {
        return $this->resistanceModifyingAbility;
    }

    public function addResistanceModifyingAbility(ResistanceModifyingAbility $resistanceModifyingAbility): self
    {
        if (!$this->resistanceModifyingAbility->contains($resistanceModifyingAbility)) {
            $this->resistanceModifyingAbility[] = $resistanceModifyingAbility;
            $resistanceModifyingAbility->addPokemon($this);
        }

        return $this;
    }

    public function removeResistanceModifyingAbility(ResistanceModifyingAbility $resistanceModifyingAbility): self
    {
        if ($this->resistanceModifyingAbility->removeElement($resistanceModifyingAbility)) {
            $resistanceModifyingAbility->removePokemon($this);
        }

        return $this;
    }

    public function getPokedexId(): ?int
    {
        return $this->pokedexId;
    }

    public function setPokedexId(int $pokedexId): self
    {
        $this->pokedexId = $pokedexId;

        return $this;
    }

    public function getOriginalForm(): ?self
    {
        return $this->originalForm;
    }

    public function setOriginalForm(?self $originalForm): self
    {
        $this->originalForm = $originalForm;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAdditionalForms(): Collection
    {
        return $this->additionalForms;
    }

    public function addAdditionalForm(self $additionalForm): self
    {
        if (!$this->additionalForms->contains($additionalForm)) {
            $this->additionalForms[] = $additionalForm;
            $additionalForm->setOriginalForm($this);
        }

        return $this;
    }

    public function removeAdditionalForm(self $additionalForm): self
    {
        if ($this->additionalForms->removeElement($additionalForm)) {
            // set the owning side to null (unless already changed)
            if ($additionalForm->getOriginalForm() === $this) {
                $additionalForm->setOriginalForm(null);
            }
        }

        return $this;
    }

    public function getEvolvedFrom(): ?self
    {
        return $this->evolvedFrom;
    }

    public function setEvolvedFrom(?self $evolvedFrom): self
    {
        $this->evolvedFrom = $evolvedFrom;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEvolutions(): Collection
    {
        return $this->evolutions;
    }

    public function addEvolution(self $evolution): self
    {
        if (!$this->evolutions->contains($evolution)) {
            $this->evolutions[] = $evolution;
            $evolution->setEvolvedFrom($this);
        }

        return $this;
    }

    public function removeEvolution(self $evolution): self
    {
        if ($this->evolutions->removeElement($evolution)) {
            // set the owning side to null (unless already changed)
            if ($evolution->getEvolvedFrom() === $this) {
                $evolution->setEvolvedFrom(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of resistancesWithAbilities
     */ 
    public function getResistancesWithAbilities()
    {
        return $this->resistancesWithAbilities;
    }

    /**
     * Set the value of resistancesWithAbilities
     *
     * @return  self
     */ 
    public function setResistancesWithAbilities($resistancesWithAbilities)
    {
        $this->resistancesWithAbilities = $resistancesWithAbilities;

        return $this;
    }
}
