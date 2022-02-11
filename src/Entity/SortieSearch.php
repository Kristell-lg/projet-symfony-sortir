<?php

namespace App\Entity;

use App\Entity\Campus;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\Date;

class SortieSearch
{
    /**
     * @var Campus
     */
    private $campus;

    /**
     * @var string
     */
    private $recherche;

    /**
     * @var DateTimeInterface
     */
    private $apresLe;

    /**
     * @var DateTimeInterface
     */
    private $avantLe;

    /**
     * @var bool
     */
    private $organisateur;

    /**
     * @var bool
     */
    private $inscrit;

    /**
     * @var bool
     */
    private $noInscrit;

    /**
     * @var bool
     */
    private $passees;

    /**
     * @var Collection|Campus[]
     */
    private $listCampus;

    /**
     * @return Campus[]|Collection
     */
    public function getListCampus()
    {
        return $this->listCampus;
    }

    /**
     * @param Campus[]|Collection $listCampus
     */
    public function setListCampus($listCampus): self
    {
        $this->listCampus = $listCampus;
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

    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    public function setRecherche(?string $recherche): self
    {
        $this->recherche = $recherche;
        return $this;
    }

    public function getApresLe(): ?DateTimeInterface
    {
        return $this->apresLe;
    }

    public function setApresLe(?DateTimeInterface $apresLe): self
    {
        $this->apresLe = $apresLe;
        return $this;
    }

    public function getAvantLe(): ?DateTimeInterface
    {
        return $this->avantLe;
    }

    public function setAvantLe(?DateTimeInterface $avantLe): self
    {
        $this->avantLe = $avantLe;
        return $this;
    }

    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): self
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): self
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    public function getNoInscrit(): ?bool
    {
        return $this->noInscrit;
    }

    public function setNoInscrit(?bool $noInscrit): self
    {
        $this->noInscrit = $noInscrit;
        return $this;
    }

    public function getPassees(): ?bool
    {
        return $this->passees;
    }

    public function setPassees(?bool $passees): self
    {
        $this->passees = $passees;
        return $this;
    }
}