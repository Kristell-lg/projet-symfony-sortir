<?php

namespace App\Entity;

class PropertySearch
{
    /**
     * @var string|null
     */
    private $recherche;

    /**
     * @return string|null
     */
    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    /**
     * @param string|null $recherche
     */
    public function setRecherche(string $recherche): void
    {
        $this->recherche = $recherche;
    }
}