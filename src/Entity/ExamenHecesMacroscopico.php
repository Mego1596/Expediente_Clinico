<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenHecesMacroscopicoRepository")
 */
class ExamenHecesMacroscopico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenHecesMacroscopico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="text")
     */
    private $aspecto;

    /**
     * @ORM\Column(type="text")
     */
    private $consistencia;

    /**
     * @ORM\Column(type="text")
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     */
    private $olor;

    /**
     * @ORM\Column(type="text")
     */
    private $presencia_de_sangre;

    /**
     * @ORM\Column(type="text")
     */
    private $restos_alimenticios;

    /**
     * @ORM\Column(type="text")
     */
    private $presencia_moco;

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

    public function getAspecto(): ?string
    {
        return $this->aspecto;
    }

    public function setAspecto(string $aspecto): self
    {
        $this->aspecto = $aspecto;

        return $this;
    }

    public function getConsistencia(): ?string
    {
        return $this->consistencia;
    }

    public function setConsistencia(string $consistencia): self
    {
        $this->consistencia = $consistencia;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getOlor(): ?string
    {
        return $this->olor;
    }

    public function setOlor(string $olor): self
    {
        $this->olor = $olor;

        return $this;
    }

    public function getPresenciaDeSangre(): ?string
    {
        return $this->presencia_de_sangre;
    }

    public function setPresenciaDeSangre(string $presencia_de_sangre): self
    {
        $this->presencia_de_sangre = $presencia_de_sangre;

        return $this;
    }

    public function getRestosAlimenticios(): ?string
    {
        return $this->restos_alimenticios;
    }

    public function setRestosAlimenticios(string $restos_alimenticios): self
    {
        $this->restos_alimenticios = $restos_alimenticios;

        return $this;
    }

    public function getPresenciaMoco(): ?string
    {
        return $this->presencia_moco;
    }

    public function setPresenciaMoco(string $presencia_moco): self
    {
        $this->presencia_moco = $presencia_moco;

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
