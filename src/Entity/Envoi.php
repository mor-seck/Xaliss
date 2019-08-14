<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="date_envoi")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Agence;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_envoi;

    /**
     * @ORM\Column(type="bigint")
     */
    private $code_envoi;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montant;

    /**
     * @ORM\Column(type="bigint")
     */
    private $commission_TTC;

    /**
     * @ORM\Column(type="bigint")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Envoyeur", inversedBy="envois")
     */
    private $envoyeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiaire", inversedBy="envois")
     */
    private $beneficiaire;

    
   


   

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

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->date_envoi;
    }

    public function setDateEnvoi(\DateTimeInterface $date_envoi): self
    {
        $this->date_envoi = $date_envoi;

        return $this;
    }

    public function getCodeEnvoi(): ?int
    {
        return $this->code_envoi;
    }

    public function setCodeEnvoi(int $code_envoi): self
    {
        $this->code_envoi = $code_envoi;

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


    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

  

    /**
     * Get the value of commission_TTC
     */ 
    public function getCommission_TTC()
    {
        return $this->commission_TTC;
    }

    /**
     * Set the value of commission_TTC
     *
     * @return  self
     */ 
    public function setCommission_TTC($commission_TTC)
    {
        $this->commission_TTC = $commission_TTC;

        return $this;
    }

    public function getEnvoyeur(): ?Envoyeur
    {
        return $this->envoyeur;
    }

    public function setEnvoyeur(?Envoyeur $envoyeur): self
    {
        $this->envoyeur = $envoyeur;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

   
}
