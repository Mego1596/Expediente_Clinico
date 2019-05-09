<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FamiliaresExpedienteRepository")
 */
class FamiliaresExpediente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Familiar", inversedBy="familiaresExpedientes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $familiar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Expediente", inversedBy="familiaresExpedientes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediente;

    /**
     * @ORM\Column(type="boolean")
     */
    private $responsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamiliar(): ?Familiar
    {
        return $this->familiar;
    }

    public function setFamiliar(?Familiar $familiar): self
    {
        $this->familiar = $familiar;

        return $this;
    }

    public function getExpediente(): ?Expediente
    {
        return $this->expediente;
    }

    public function setExpediente(?Expediente $expediente): self
    {
        $this->expediente = $expediente;

        return $this;
    }

    public function getResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(bool $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

     public function getCreadoEn(): ?\DateTimeInterface
    {
        return $this->creado_en;
    }

    public function setCreadoEn(?\DateTimeInterface $creado_en): self
    {
        $this->creado_en = $creado_en;

        return $this;
    }

    public function getActualizadoEn(): ?\DateTimeInterface
    {
        return $this->actualizado_en;
    }

    public function setActualizadoEn(?\DateTimeInterface $actualizado_en): self
    {
        $this->actualizado_en = $actualizado_en;

        return $this;
    }
}
