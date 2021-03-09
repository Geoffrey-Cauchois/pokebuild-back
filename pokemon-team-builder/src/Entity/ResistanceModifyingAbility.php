<?php

namespace App\Entity;

use App\Repository\ResistanceModifyingAbilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResistanceModifyingAbilityRepository::class)
 */
class ResistanceModifyingAbility
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
     * @ORM\Column(type="float")
     */
    private $multiplier;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="resistanceModifyingAbility")
     */
    private $modifiedType;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="resistanceModifyingAbility")
     */
    private $pokemon;

    public function __construct()
    {
        $this->modifiedType = new ArrayCollection();
        $this->pokemon = new ArrayCollection();
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

    public function getMultiplier(): ?float
    {
        return $this->multiplier;
    }

    public function setMultiplier(float $multiplier): self
    {
        $this->multiplier = $multiplier;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getModifiedType(): Collection
    {
        return $this->modifiedType;
    }

    public function addModifiedType(Type $modifiedType): self
    {
        if (!$this->modifiedType->contains($modifiedType)) {
            $this->modifiedType[] = $modifiedType;
        }

        return $this;
    }

    public function removeModifiedType(Type $modifiedType): self
    {
        $this->modifiedType->removeElement($modifiedType);

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
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->pokemon->removeElement($pokemon);

        return $this;
    }
}
