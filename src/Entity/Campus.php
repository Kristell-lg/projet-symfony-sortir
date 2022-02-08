<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="campus")
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="campus")
     */
    private $participants;

    public function __construct()
    {
        $this->campus = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getCampus(): Collection
    {
        return $this->campus;
    }

    public function addCampus(Sorties $campus): self
    {
        if (!$this->campus->contains($campus)) {
            $this->campus[] = $campus;
            $campus->setCampus($this);
        }

        return $this;
    }

    public function removeCampus(Sorties $campus): self
    {
        if ($this->campus->removeElement($campus)) {
            // set the owning side to null (unless already changed)
            if ($campus->getCampus() === $this) {
                $campus->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setCampus($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getCampus() === $this) {
                $participant->setCampus(null);
            }
        }

        return $this;
    }
}
