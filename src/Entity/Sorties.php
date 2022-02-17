<?php

namespace App\Entity;

use App\Repository\SortiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortiesRepository::class)
 */
class Sorties
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
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $infosSortie;


    /**
     * @ORM\ManyToOne(targetEntity=Lieux::class, inversedBy="lieu")
     */
    private $lieux;

    /**
     * @ORM\ManyToOne(targetEntity=Etats::class, inversedBy="etat")
     */
    private $etats;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="campus")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="sorties", orphanRemoval=true)
     */
    private $sortieParticipants;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sortiesOrganisees")
     */
    private $organisateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $typeDeSortie;

    public function __construct()
    {
        $this->sortieParticipants = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }


    public function getLieux(): ?Lieux
    {
        return $this->lieux;
    }

    public function setLieux(?Lieux $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getEtats(): ?Etats
    {
        return $this->etats;
    }

    public function setEtats(?Etats $etats): self
    {
        $this->etats = $etats;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getSortieParticipants(): Collection
    {
        return $this->sortieParticipants;
    }

    public function addSortieParticipants(Participant $sortie): self
    {
        if (!$this->sortieParticipants->contains($sortie)) {
            $this->sortieParticipants[] = $sortie;
        }

        return $this;
    }


    public function removeSortie(Participant $sortie): self
    {
        $this->sortieParticipants->removeElement($sortie);

        return $this;
    }



    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $orga): self
    {
        $this->organisateur = $orga;

        return $this;
    }

    public function getTypeDeSortie(): ?bool
    {
        return $this->typeDeSortie;
    }

    public function setTypeDeSortie(bool $typeDeSortie): self
    {
        $this->typeDeSortie = $typeDeSortie;

        return $this;
    }
}
