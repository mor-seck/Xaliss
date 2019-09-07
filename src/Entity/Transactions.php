<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionsRepository")
 */
class Transactions
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
    private $type;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCompleEnv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCompleBen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telenv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $teleben;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $numpieceenv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typepieceenv;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $numpieceben;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typepieceben;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateenv;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateretrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="transactions")
     */
    private $agentenv;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="transactions")
     */
    private $agentretrait;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $commissionSystem;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $commissionagentenv;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $commissionagentretrait;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $commissionetat;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $frais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     */
    private $compte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNomCompleEnv(): ?string
    {
        return $this->nomCompleEnv;
    }

    public function setNomCompleEnv(?string $nomCompleEnv): self
    {
        $this->nomCompleEnv = $nomCompleEnv;

        return $this;
    }

    public function getNomCompleBen(): ?string
    {
        return $this->nomCompleBen;
    }

    public function setNomCompleBen(?string $nomCompleBen): self
    {
        $this->nomCompleBen = $nomCompleBen;

        return $this;
    }

    public function getTelenv(): ?string
    {
        return $this->telenv;
    }

    public function setTelenv(?string $telenv): self
    {
        $this->telenv = $telenv;

        return $this;
    }

    public function getTeleben(): ?string
    {
        return $this->teleben;
    }

    public function setTeleben(?string $teleben): self
    {
        $this->teleben = $teleben;

        return $this;
    }

    public function getNumpieceenv(): ?int
    {
        return $this->numpieceenv;
    }

    public function setNumpieceenv(?int $numpieceenv): self
    {
        $this->numpieceenv = $numpieceenv;

        return $this;
    }

    public function getTypepieceenv(): ?string
    {
        return $this->typepieceenv;
    }

    public function setTypepieceenv(?string $typepieceenv): self
    {
        $this->typepieceenv = $typepieceenv;

        return $this;
    }

    public function getNumpieceben(): ?int
    {
        return $this->numpieceben;
    }

    public function setNumpieceben(?int $numpieceben): self
    {
        $this->numpieceben = $numpieceben;

        return $this;
    }

    public function getTypepieceben(): ?string
    {
        return $this->typepieceben;
    }

    public function setTypepieceben(?string $typepieceben): self
    {
        $this->typepieceben = $typepieceben;

        return $this;
    }

    public function getDateenv(): ?\DateTimeInterface
    {
        return $this->dateenv;
    }

    public function setDateenv(?\DateTimeInterface $dateenv): self
    {
        $this->dateenv = $dateenv;

        return $this;
    }

    public function getDateretrait(): ?\DateTimeInterface
    {
        return $this->dateretrait;
    }

    public function setDateretrait(?\DateTimeInterface $dateretrait): self
    {
        $this->dateretrait = $dateretrait;

        return $this;
    }

    public function getAgentenv(): ?Utilisateur
    {
        return $this->agentenv;
    }

    public function setAgentenv(?Utilisateur $agentenv): self
    {
        $this->agentenv = $agentenv;

        return $this;
    }

    public function getAgentretrait(): ?Utilisateur
    {
        return $this->agentretrait;
    }

    public function setAgentretrait(?Utilisateur $agentretrait): self
    {
        $this->agentretrait = $agentretrait;

        return $this;
    }

    public function getCommissionSystem(): ?int
    {
        return $this->commissionSystem;
    }

    public function setCommissionSystem(?int $commissionSystem): self
    {
        $this->commissionSystem = $commissionSystem;

        return $this;
    }

    public function getCommissionagentenv(): ?int
    {
        return $this->commissionagentenv;
    }

    public function setCommissionagentenv(?int $commissionagentenv): self
    {
        $this->commissionagentenv = $commissionagentenv;

        return $this;
    }

    public function getCommissionagentretrait(): ?int
    {
        return $this->commissionagentretrait;
    }

    public function setCommissionagentretrait(?int $commissionagentretrait): self
    {
        $this->commissionagentretrait = $commissionagentretrait;

        return $this;
    }

    public function getCommissionetat(): ?int
    {
        return $this->commissionetat;
    }

    public function setCommissionetat(?int $commissionetat): self
    {
        $this->commissionetat = $commissionetat;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFrais(): ?string
    {
        return $this->frais;
    }

    public function setFrais(?string $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}