<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SignoVitalRepository")
 */
class SignoVital
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cita", inversedBy="signoVital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cita;

    /**
     * @ORM\Column(type="float")
     */
    private $peso;

    /**
     * @ORM\Column(type="float")
     */
    private $temperatura;

    /**
     * @ORM\Column(type="float")
     */
    private $estatura;

    /**
     * @ORM\Column(type="float")
     */
    private $presionArterial;

    /**
     * @ORM\Column(type="float")
     */
    private $ritmoCardiaco;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCita(): ?Cita
    {
        return $this->cita;
    }

    public function setCita(Cita $cita): self
    {
        $this->cita = $cita;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getTemperatura(): ?float
    {
        return $this->temperatura;
    }

    public function setTemperatura(float $temperatura): self
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    public function getEstatura(): ?float
    {
        return $this->estatura;
    }

    public function setEstatura(float $estatura): self
    {
        $this->estatura = $estatura;

        return $this;
    }

    public function getPresionArterial(): ?float
    {
        return $this->presionArterial;
    }

    public function setPresionArterial(float $presionArterial): self
    {
        $this->presionArterial = $presionArterial;

        return $this;
    }

    public function getRitmoCardiaco(): ?float
    {
        return $this->ritmoCardiaco;
    }

    public function setRitmoCardiaco(float $ritmoCardiaco): self
    {
        $this->ritmoCardiaco = $ritmoCardiaco;

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
