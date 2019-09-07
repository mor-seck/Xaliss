<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TarifRepository")
 */
class Tarif
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $borneinferieur;

    /**
     * @ORM\Column(type="bigint")
     */
    private $bornesuperieur;

    /**
     * @ORM\Column(type="bigint")
     */
    private $valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneinferieur(): ?int
    {
        return $this->borneinferieur;
    }

    public function setBorneinferieur(int $borneinferieur): self
    {
        $this->borneinferieur = $borneinferieur;

        return $this;
    }

    public function getBornesuperieur(): ?int
    {
        return $this->bornesuperieur;
    }

    public function setBornesuperieur(int $bornesuperieur): self
    {
        $this->bornesuperieur = $bornesuperieur;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }
}
