<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenHecesQuimicoRepository")
 */
class ExamenHecesQuimico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $idExamen_Heces_Quimico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenHecesQuimico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="float")
     */
    private $ph;

    /**
     * @ORM\Column(type="text")
     */
    private $azucares_reductores;

    /**
     * @ORM\Column(type="text")
     */
    private $sangre_oculta;

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

    public function getExamenSolicitado(): ?ExamenSolicitado
    {
        return $this->examen_solicitado;
    }

    public function setExamenSolicitado(ExamenSolicitado $examen_solicitado): self
    {
        $this->examen_solicitado = $examen_solicitado;

        return $this;
    }

    public function getPh(): ?float
    {
        return $this->ph;
    }

    public function setPh(float $ph): self
    {
        $this->ph = $ph;

        return $this;
    }

    public function getAzucaresReductores(): ?string
    {
        return $this->azucares_reductores;
    }

    public function setAzucaresReductores(string $azucares_reductores): self
    {
        $this->azucares_reductores = $azucares_reductores;

        return $this;
    }

    public function getSangreOculta(): ?string
    {
        return $this->sangre_oculta;
    }

    public function setSangreOculta(string $sangre_oculta): self
    {
        $this->sangre_oculta = $sangre_oculta;

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
