<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistorialIngresadoRepository")
 */
class HistorialIngresado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombreSala;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroHabitacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroCamilla;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $doctorAsignado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaEntrada;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaSalida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Expediente", inversedBy="historialIngresados")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSala(): ?string
    {
        return $this->nombre_sala;
    }

    public function setNombreSala(string $nombre_sala): self
    {
        $this->nombre_sala = $nombre_sala;

        return $this;
    }

    public function getNumeroHabitacion(): ?int
    {
        return $this->numero_habitacion;
    }

    public function setNumeroHabitacion(int $numero_habitacion): self
    {
        $this->numero_habitacion = $numero_habitacion;

        return $this;
    }

    public function getNumeroCamilla(): ?int
    {
        return $this->numero_camilla;
    }

    public function setNumeroCamilla(int $numero_camilla): self
    {
        $this->numero_camilla = $numero_camilla;

        return $this;
    }

    public function getDoctorAsignado(): ?string
    {
        return $this->doctorAsignado;
    }

    public function setDoctorAsignado(string $doctorAsignado): self
    {
        $this->doctorAsignado = $doctorAsignado;

        return $this;
    }

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fechaEntrada;
    }

    public function setFechaEntrada(\DateTimeInterface $fechaEntrada): self
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    public function getFechaSalida(): ?\DateTimeInterface
    {
        return $this->fechaSalida;
    }

    public function setFechaSalida(\DateTimeInterface $fechaSalida): self
    {
        $this->fechaSalida = $fechaSalida;

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
}
