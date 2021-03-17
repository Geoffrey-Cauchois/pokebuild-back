<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="types", fetch="EAGER")
     * @Ignore()
     */
    private $pokemon;


    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="vulnerability", fetch="EAGER")
     * @JoinTable(name="vulnerable_to",
     *      joinColumns={@JoinColumn(name="attacked_type", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="attacking_type", referencedColumnName="id")}
     *      )
     * @Ignore()
     */
    private $vulnerable_to;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="vulnerable_to")
     * @Ignore()
     */
    private $vulnerability;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="resistance", fetch="EAGER")
     * @JoinTable(name="resistant_to",
     *      joinColumns={@JoinColumn(name="attacked_type", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="attacking_type", referencedColumnName="id")}
     *      )
     * @Ignore()
     */
    private $resistant_to;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="resistant_to")
     * @Ignore()
     */
    private $resistance;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="neutrality", fetch="EAGER")
     * @JoinTable(name="neutral_to",
     *      joinColumns={@JoinColumn(name="attacked_type", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="attacking_type", referencedColumnName="id")}
     *      )
     * @Ignore()
     */
    private $neutral_to;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="neutral_to")
     * @Ignore()
     */
    private $neutrality;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="immunity", fetch="EAGER")
     * @JoinTable(name="immune_to",
     *      joinColumns={@JoinColumn(name="attacked_type", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="attacking_type", referencedColumnName="id")}
     *      )
     * @Ignore()
     */
    private $immune_to;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="immune_to")
     * @Ignore()
     */
    private $immunity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Ignore()
     */
    private $english_name;

    /**
     * @ORM\ManyToMany(targetEntity=ResistanceModifyingAbility::class, mappedBy="modifiedType")
     * @Ignore()
     */
    private $resistanceModifyingAbility;



    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
        $this->vulnerable_to = new ArrayCollection();
        $this->vulnerability = new ArrayCollection();
        $this->resistant_to = new ArrayCollection();
        $this->resistance = new ArrayCollection();
        $this->neutral_to = new ArrayCollection();
        $this->neutrality = new ArrayCollection();
        $this->immune_to = new ArrayCollection();
        $this->immunity = new ArrayCollection();
        $this->resistanceModifyingAbility = new ArrayCollection();
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
    
    /**
     * @return Collection|self[]
     * @Ignore()
     */
    public function getVulnerableTo(): Collection
    {
        return $this->vulnerable_to;
    }

    public function addVulnerableTo(self $vulnerableTo): self
    {
        if (!$this->vulnerable_to->contains($vulnerableTo)) {
            $this->vulnerable_to[] = $vulnerableTo;
        }

        return $this;
    }

    public function removeVulnerableTo(self $vulnerableTo): self
    {
        $this->vulnerable_to->removeElement($vulnerableTo);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getVulnerability(): Collection
    {
        return $this->vulnerability;
    }

    public function addVulnerability(self $vulnerability): self
    {
        if (!$this->vulnerability->contains($vulnerability)) {
            $this->vulnerability[] = $vulnerability;
            $vulnerability->addVulnerableTo($this);
        }

        return $this;
    }

    public function removeVulnerability(self $vulnerability): self
    {
        if ($this->vulnerability->removeElement($vulnerability)) {
            $vulnerability->removeVulnerableTo($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     * @Ignore()
     */
    public function getResistantTo(): Collection
    {
        return $this->resistant_to;
    }

    public function addResistantTo(self $resistantTo): self
    {
        if (!$this->resistant_to->contains($resistantTo)) {
            $this->resistant_to[] = $resistantTo;
        }

        return $this;
    }

    public function removeResistantTo(self $resistantTo): self
    {
        $this->resistant_to->removeElement($resistantTo);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getResistance(): Collection
    {
        return $this->resistance;
    }

    public function addResistance(self $resistance): self
    {
        if (!$this->resistance->contains($resistance)) {
            $this->resistance[] = $resistance;
            $resistance->addResistantTo($this);
        }

        return $this;
    }

    public function removeResistance(self $resistance): self
    {
        if ($this->resistance->removeElement($resistance)) {
            $resistance->removeResistantTo($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     * @Ignore()
     */
    public function getNeutralTo(): Collection
    {
        return $this->neutral_to;
    }

    public function addNeutralTo(self $neutralTo): self
    {
        if (!$this->neutral_to->contains($neutralTo)) {
            $this->neutral_to[] = $neutralTo;
        }

        return $this;
    }

    public function removeNeutralTo(self $neutralTo): self
    {
        $this->neutral_to->removeElement($neutralTo);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getNeutrality(): Collection
    {
        return $this->neutrality;
    }

    public function addNeutrality(self $neutrality): self
    {
        if (!$this->neutrality->contains($neutrality)) {
            $this->neutrality[] = $neutrality;
            $neutrality->addNeutralTo($this);
        }

        return $this;
    }

    public function removeNeutrality(self $neutrality): self
    {
        if ($this->neutrality->removeElement($neutrality)) {
            $neutrality->removeNeutralTo($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     * @Ignore()
     */
    public function getImmuneTo(): Collection
    {
        return $this->immune_to;
    }

    public function addImmuneTo(self $immuneTo): self
    {
        if (!$this->immune_to->contains($immuneTo)) {
            $this->immune_to[] = $immuneTo;
        }

        return $this;
    }

    public function removeImmuneTo(self $immuneTo): self
    {
        $this->immune_to->removeElement($immuneTo);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getImmunity(): Collection
    {
        return $this->immunity;
    }

    public function addImmunity(self $immunity): self
    {
        if (!$this->immunity->contains($immunity)) {
            $this->immunity[] = $immunity;
            $immunity->addImmuneTo($this);
        }

        return $this;
    }

    public function removeImmunity(self $immunity): self
    {
        if ($this->immunity->removeElement($immunity)) {
            $immunity->removeImmuneTo($this);
        }

        return $this;
    }

    public function getEnglishName(): ?string
    {
        return $this->english_name;
    }

    public function setEnglishName(string $english_name): self
    {
        $this->english_name = $english_name;

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
            $resistanceModifyingAbility->addModifiedType($this);
        }

        return $this;
    }

    public function removeResistanceModifyingAbility(ResistanceModifyingAbility $resistanceModifyingAbility): self
    {
        if ($this->resistanceModifyingAbility->removeElement($resistanceModifyingAbility)) {
            $resistanceModifyingAbility->removeModifiedType($this);
        }

        return $this;
    }

}
