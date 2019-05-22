<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriaMedicaRepository")
 */
class HistoriaMedica
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cita", inversedBy="historiaMedica", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cita;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Diagnostico", inversedBy="historiaMedica", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $diagnostico;

    /**
     * @ORM\Column(type="text")
     */
    private $consultaPor;

    /**
     * @ORM\Column(type="text")
     */
    private $signos;

    /**
     * @ORM\Column(type="text")
     */
    private $sintomas;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $codigoEspecifico;

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

    public function getDiagnostico(): ?Diagnostico
    {
        return $this->diagnostico;
    }

    public function setDiagnostico(Diagnostico $diagnostico): self
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    public function getConsultaPor(): ?string
    {
        return $this->consultaPor;
    }

    public function setConsultaPor(string $consultaPor): self
    {
        $this->consultaPor = $consultaPor;

        return $this;
    }

    public function getSignos(): ?string
    {
        return $this->signos;
    }

    public function setSignos(string $signos): self
    {
        $this->signos = $signos;

        return $this;
    }

    public function getSintomas(): ?string
    {
        return $this->sintomas;
    }

    public function setSintomas(string $sintomas): self
    {
        $this->sintomas = $sintomas;

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

    public function getCodigoEspecifico(): ?string
    {
        return $this->codigoEspecifico;
    }

    public function setCodigoEspecifico(string $codigoEspecifico): self
    {
        $this->codigoEspecifico = $codigoEspecifico;

        return $this;
    }
}
