<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoHabitacionRepository")
 */
class TipoHabitacion
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
    private $tipoHabitacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Habitacion", mappedBy="tipoHabitacion")
     */
    private $habitaciones;

    public function __construct()
    {
        $this->habitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoHabitacion(): ?string
    {
        return $this->tipoHabitacion;
    }

    public function setTipoHabitacion(string $tipoHabitacion): self
    {
        $this->tipoHabitacion = $tipoHabitacion;

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

    /**
     * @return Collection|Habitacion[]
     */
    public function getHabitaciones(): Collection
    {
        return $this->habitaciones;
    }

    public function addHabitaciones(Habitacion $habitacione): self
    {
        if (!$this->habitaciones->contains($habitacione)) {
            $this->habitaciones[] = $habitacione;
            $habitacione->setTipoHabitacion($this);
        }

        return $this;
    }

    public function removeHabitaciones(Habitacion $habitacione): self
    {
        if ($this->habitaciones->contains($habitacione)) {
            $this->habitaciones->removeElement($habitacione);
            // set the owning side to null (unless already changed)
            if ($habitacione->getTipoHabitacion() === $this) {
                $habitacione->setTipoHabitacion(null);
            }
        }

        return $this;
    }

    public function addHabitacione(Habitacion $habitacione): self
    {
        if (!$this->habitaciones->contains($habitacione)) {
            $this->habitaciones[] = $habitacione;
            $habitacione->setTipoHabitacion($this);
        }

        return $this;
    }

    public function removeHabitacione(Habitacion $habitacione): self
    {
        if ($this->habitaciones->contains($habitacione)) {
            $this->habitaciones->removeElement($habitacione);
            // set the owning side to null (unless already changed)
            if ($habitacione->getTipoHabitacion() === $this) {
                $habitacione->setTipoHabitacion(null);
            }
        }

        return $this;
    }
}
