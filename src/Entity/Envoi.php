<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EnvoiRepository")
 */
class Envoi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="date_envoi",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *  @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     *  @Assert\Valid()
     */
    private $Agence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomenv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numtelenv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $piece_env;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $num_piece_env;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomben;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telben;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $montantenvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commission;

   
    
  
    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomenv(): ?string
    {
        return $this->nomenv;
    }

    public function setNomenv(string $nomenv): self
    {
        $this->nomenv = $nomenv;

        return $this;
    }

    public function getNumtelenv(): ?string
    {
        return $this->numtelenv;
    }

    public function setNumtelenv(string $numtelenv): self
    {
        $this->numtelenv = $numtelenv;

        return $this;
    }

    public function getPieceEnv(): ?string
    {
        return $this->piece_env;
    }

    public function setPieceEnv(string $piece_env): self
    {
        $this->piece_env = $piece_env;

        return $this;
    }

    public function getNumPieceEnv(): ?string
    {
        return $this->num_piece_env;
    }

    public function setNumPieceEnv(string $num_piece_env): self
    {
        $this->num_piece_env = $num_piece_env;

        return $this;
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

    public function getTelben(): ?string
    {
        return $this->telben;
    }

    public function setTelben(string $telben): self
    {
        $this->telben = $telben;

        return $this;
    }

    public function getMontantenvoi(): ?string
    {
        return $this->montantenvoi;
    }

    public function setMontantenvoi(string $montantenvoi): self
    {
        $this->montantenvoi = $montantenvoi;

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
