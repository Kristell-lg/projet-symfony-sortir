<?php

namespace App\Entity;

use App\Repository\EtatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatsRepository::class)
 */
class Etats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="etats")
     */
    private $etat;

    public function __construct()
    {
        $this->etat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getEtat(): Collection
    {
        return $this->etat;
    }

    public function addEtat(Sorties $etat): self
    {
        if (!$this->etat->contains($etat)) {
            $this->etat[] = $etat;
            $etat->setEtats($this);
        }

        return $this;
    }

    public function removeEtat(Sorties $etat): self
    {
        if ($this->etat->removeElement($etat)) {
            // set the owning side to null (unless already changed)
            if ($etat->getEtats() === $this) {
                $etat->setEtats(null);
            }
        }

        return $this;
    }
}
