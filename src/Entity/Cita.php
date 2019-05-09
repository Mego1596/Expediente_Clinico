<?php

namespace App\Entity;

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
     * @ORM\JoinColumn(nullable=false)
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
     * @ORM\OneToOne(targetEntity="App\Entity\CitaExamen", mappedBy="cita", cascade={"persist", "remove"})
     */
    private $citaExamen;

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

    public function getCitaExamen(): ?CitaExamen
    {
        return $this->citaExamen;
    }

    public function setCitaExamen(CitaExamen $citaExamen): self
    {
        $this->citaExamen = $citaExamen;

        // set the owning side of the relation if necessary
        if ($this !== $citaExamen->getCita()) {
            $citaExamen->setCita($this);
        }

        return $this;
    }
}
