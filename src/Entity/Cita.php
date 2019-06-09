<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CitaRepository")
 */
class Cita
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="citas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Expediente", inversedBy="citas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediente;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaReservacion;

    /**
     * @ORM\Column(type="text")
     */
    private $consultaPor;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SignoVital", mappedBy="cita", cascade={"persist", "remove"})
     */
    private $signoVital;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\HistoriaMedica", mappedBy="cita", cascade={"persist", "remove"})
     */
    private $historiaMedica;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFin=null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamenSolicitado", mappedBy="cita",cascade={"persist", "remove"})
     */
    private $examenes;

    public function __construct()
    {
        $this->examenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

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

    public function getFechaReservacion(): ?\DateTimeInterface
    {
        return $this->fechaReservacion;
    }

    public function setFechaReservacion(\DateTimeInterface $fechaReservacion): self
    {
        $this->fechaReservacion = $fechaReservacion;

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

    public function getSignoVital(): ?SignoVital
    {
        return $this->signoVital;
    }

    public function setSignoVital(SignoVital $signoVital): self
    {
        $this->signoVital = $signoVital;

        // set the owning side of the relation if necessary
        if ($this !== $signoVital->getCita()) {
            $signoVital->setCita($this);
        }

        return $this;
    }

    public function getHistoriaMedica(): ?HistoriaMedica
    {
        return $this->historiaMedica;
    }

    public function setHistoriaMedica(HistoriaMedica $historiaMedica): self
    {
        $this->historiaMedica = $historiaMedica;

        // set the owning side of the relation if necessary
        if ($this !== $historiaMedica->getCita()) {
            $historiaMedica->setCita($this);
        }

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * @return Collection|ExamenSolicitado[]
     */
    public function getExamenes(): Collection
    {
        return $this->examenes;
    }

    public function addExamene(ExamenSolicitado $examene): self
    {
        if (!$this->examenes->contains($examene)) {
            $this->examenes[] = $examene;
            $examene->setCita($this);
        }

        return $this;
    }

    public function removeExamene(ExamenSolicitado $examene): self
    {
        if ($this->examenes->contains($examene)) {
            $this->examenes->removeElement($examene);
            // set the owning side to null (unless already changed)
            if ($examene->getCita() === $this) {
                $examene->setCita(null);
            }
        }

        return $this;
    }
}
