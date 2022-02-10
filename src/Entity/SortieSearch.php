<?php

namespace App\Entity;

use App\Entity\Campus;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Boolean;
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
     * @var Date
     */
    private $apresLe;

    /**
     * @var Date
     */
    private $avantLe;

    /**
     * @var Boolean
     */
    private $organisateur;

    /**
     * @var Boolean
     */
    private $inscrit;

    /**
     * @var Boolean
     */
    private $noInscrit;

    /**
     * @var Boolean
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

    public function getApresLe(): ?Date
    {
        return $this->apresLe;
    }

    public function setApresLe(?Date $apresLe): self
    {
        $this->apresLe = $apresLe;
        return $this;
    }

    public function getAvantLe(): ?Date
    {
        return $this->avantLe;
    }

    public function setAvantLe(?Date $avantLe): self
    {
        $this->avantLe = $avantLe;
        return $this;
    }

    public function getOrganisateur(): ?Boolean
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Boolean $organisateur): self
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    public function getInscrit(): ?Boolean
    {
        return $this->inscrit;
    }

    public function setInscrit(?Boolean $inscrit): self
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    public function getNoInscrit(): ?Boolean
    {
        return $this->noInscrit;
    }

    public function setNoInscrit(?Boolean $noInscrit): self
    {
        $this->noInscrit = $noInscrit;
        return $this;
    }

    public function getPassees(): ?Boolean
    {
        return $this->passees;
    }

    public function setPassees(?Boolean $passees): self
    {
        $this->passees = $passees;
        return $this;
    }
}