<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlanTratamientoRepository")
 */
class PlanTratamiento
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text")
     */
    private $dosis;

    /**
     * @ORM\Column(type="text")
     */
    private $medicamento;

    /**
     * @ORM\Column(type="text")
     */
    private $frecuencia;

    /**
     * @ORM\Column(type="text")
     */
    private $tipoMedicamento;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HistoriaMedica", inversedBy="planTratamientos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historiaMedica;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDosis(): ?string
    {
        return $this->dosis;
    }

    public function setDosis(string $dosis): self
    {
        $this->dosis = $dosis;

        return $this;
    }

    public function getMedicamento(): ?string
    {
        return $this->medicamento;
    }

    public function setMedicamento(string $medicamento): self
    {
        $this->medicamento = $medicamento;

        return $this;
    }

    public function getFrecuencia(): ?string
    {
        return $this->frecuencia;
    }

    public function setFrecuencia(string $frecuencia): self
    {
        $this->frecuencia = $frecuencia;

        return $this;
    }

    public function getTipoMedicamento(): ?string
    {
        return $this->tipoMedicamento;
    }

    public function setTipoMedicamento(string $tipoMedicamento): self
    {
        $this->tipoMedicamento = $tipoMedicamento;

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

    public function getHistoriaMedica(): ?HistoriaMedica
    {
        return $this->historiaMedica;
    }

    public function setHistoriaMedica(?HistoriaMedica $historiaMedica): self
    {
        $this->historiaMedica = $historiaMedica;

        return $this;
    }
}
