<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenOrinaCristaluriaRepository")
 */
class ExamenOrinaCristaluria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenOrinaCristaluria", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="text")
     */
    private $uratosAmorfos;

    /**
     * @ORM\Column(type="text")
     */
    private $acidoUrico;

    /**
     * @ORM\Column(type="text")
     */
    private $oxalatosCalcicos;

    /**
     * @ORM\Column(type="text")
     */
    private $fosfatosAmorfos;

    /**
     * @ORM\Column(type="text")
     */
    private $fosfatosCalcicos;

    /**
     * @ORM\Column(type="text")
     */
    private $fosfatosAmonicos;

    /**
     * @ORM\Column(type="text")
     */
    private $riesgoLitogenico;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime")
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

    public function getUratosAmorfos(): ?string
    {
        return $this->uratosAmorfos;
    }

    public function setUratosAmorfos(string $uratosAmorfos): self
    {
        $this->uratosAmorfos = $uratosAmorfos;

        return $this;
    }

    public function getAcidoUrico(): ?string
    {
        return $this->acidoUrico;
    }

    public function setAcidoUrico(string $acidoUrico): self
    {
        $this->acidoUrico = $acidoUrico;

        return $this;
    }

    public function getOxalatosCalcicos(): ?string
    {
        return $this->oxalatosCalcicos;
    }

    public function setOxalatosCalcicos(string $oxalatosCalcicos): self
    {
        $this->oxalatosCalcicos = $oxalatosCalcicos;

        return $this;
    }

    public function getFosfatosAmorfos(): ?string
    {
        return $this->fosfatosAmorfos;
    }

    public function setFosfatosAmorfos(string $fosfatosAmorfos): self
    {
        $this->fosfatosAmorfos = $fosfatosAmorfos;

        return $this;
    }

    public function getFosfatosCalcicos(): ?string
    {
        return $this->fosfatosCalcicos;
    }

    public function setFosfatosCalcicos(string $fosfatosCalcicos): self
    {
        $this->fosfatosCalcicos = $fosfatosCalcicos;

        return $this;
    }

    public function getFosfatosAmonicos(): ?string
    {
        return $this->fosfatosAmonicos;
    }

    public function setFosfatosAmonicos(string $fosfatosAmonicos): self
    {
        $this->fosfatosAmonicos = $fosfatosAmonicos;

        return $this;
    }

    public function getRiesgoLitogenico(): ?string
    {
        return $this->riesgoLitogenico;
    }

    public function setRiesgoLitogenico(string $riesgoLitogenico): self
    {
        $this->riesgoLitogenico = $riesgoLitogenico;

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

    public function setActualizadoEn(\DateTimeInterface $actualizado_en): self
    {
        $this->actualizado_en = $actualizado_en;

        return $this;
    }
}
