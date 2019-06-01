<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenQuimicaSanguineaRepository")
 */
class ExamenQuimicaSanguinea
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenQuimicaSanguineas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="text")
     */
    private $parametro;

    /**
     * @ORM\Column(type="integer")
     */
    private $resultado;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @ORM\Column(type="text")
     */
    private $unidades;

    /**
     * @ORM\Column(type="text")
     */
    private $rango;

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

    public function getParametro(): ?string
    {
        return $this->parametro;
    }

    public function setParametro(string $parametro): self
    {
        $this->parametro = $parametro;

        return $this;
    }

    public function getResultado(): ?int
    {
        return $this->resultado;
    }

    public function setResultado(int $resultado): self
    {
        $this->resultado = $resultado;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getUnidades(): ?string
    {
        return $this->unidades;
    }

    public function setUnidades(string $unidades): self
    {
        $this->unidades = $unidades;

        return $this;
    }

    public function getRango(): ?string
    {
        return $this->rango;
    }

    public function setRango(string $rango): self
    {
        $this->rango = $rango;

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

    public function getExamenSolicitado(): ?ExamenSolicitado
    {
        return $this->examen_solicitado;
    }

    public function setExamenSolicitado(?ExamenSolicitado $examen_solicitado): self
    {
        $this->examen_solicitado = $examen_solicitado;

        return $this;
    }
}
