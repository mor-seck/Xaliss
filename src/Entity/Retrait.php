<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\RetraitRepository")
 */
class Retrait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomben;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pieceben;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numpieceben;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="retraits",cascade={"persist"})
     *  @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     *  @Assert\Valid()
     */
    private $Agence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomben(): ?string
    {
        return $this->nomben;
    }

    public function setNomben(string $nomben): self
    {
        $this->nomben = $nomben;

        return $this;
    }

    public function getPieceben(): ?string
    {
        return $this->pieceben;
    }

    public function setPieceben(string $pieceben): self
    {
        $this->pieceben = $pieceben;

        return $this;
    }

    public function getNumpieceben(): ?string
    {
        return $this->numpieceben;
    }

    public function setNumpieceben(string $numpieceben): self
    {
        $this->numpieceben = $numpieceben;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getAgence(): ?Partenaire
    {
        return $this->Agence;
    }

    public function setAgence(?Partenaire $Agence): self
    {
        $this->Agence = $Agence;

        return $this;
    }

    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(string $commission): self
    {
        $this->commission = $commission;

        return $this;
    }
}
