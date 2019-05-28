<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenHecesMicroscopicoRepository")
 */
class ExamenHecesMicroscopico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenHecesMicroscopico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="text")
     */
    private $hematies;

    /**
     * @ORM\Column(type="text")
     */
    private $leucocito;

    /**
     * @ORM\Column(type="text")
     */
    private $floraBacteriana;

    /**
     * @ORM\Column(type="text")
     */
    private $levadura;

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

    public function getHematies(): ?string
    {
        return $this->hematies;
    }

    public function setHematies(string $hematies): self
    {
        $this->hematies = $hematies;

        return $this;
    }

    public function getLeucocito(): ?string
    {
        return $this->leucocito;
    }

    public function setLeucocito(string $leucocito): self
    {
        $this->leucocito = $leucocito;

        return $this;
    }

    public function getFloraBacteriana(): ?string
    {
        return $this->floraBacteriana;
    }

    public function setFloraBacteriana(string $floraBacteriana): self
    {
        $this->floraBacteriana = $floraBacteriana;

        return $this;
    }

    public function getLevadura(): ?string
    {
        return $this->levadura;
    }

    public function setLevadura(string $levadura): self
    {
        $this->levadura = $levadura;

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
