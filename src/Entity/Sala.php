<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalaRepository")
 */
class Sala
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
    private $nombreSala;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clinica", inversedBy="salas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinica;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Habitacion", mappedBy="sala")
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

    public function getNombreSala(): ?string
    {
        return $this->nombreSala;
    }

    public function setNombreSala(string $nombreSala): self
    {
        $this->nombreSala = $nombreSala;

        return $this;
    }

    public function getClinica(): ?Clinica
    {
        return $this->clinica;
    }

    public function setClinica(?Clinica $clinica): self
    {
        $this->clinica = $clinica;

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

    public function addHabitacione(Habitacion $habitacion): self
    {
        if (!$this->habitaciones->contains($habitacion)) {
            $this->habitaciones[] = $habitacion;
            $habitacione->setSala($this);
        }

        return $this;
    }

    public function removeHabitaciones(Habitacion $habitacion): self
    {
        if ($this->habitaciones->contains($habitacion)) {
            $this->habitaciones->removeElement($habitacion);
            // set the owning side to null (unless already changed)
            if ($habitacion->getSala() === $this) {
                $habitacion->setSala(null);
            }
        }

        return $this;
    }

    public function removeHabitacione(Habitacion $habitacione): self
    {
        if ($this->habitaciones->contains($habitacione)) {
            $this->habitaciones->removeElement($habitacione);
            // set the owning side to null (unless already changed)
            if ($habitacione->getSala() === $this) {
                $habitacione->setSala(null);
            }
        }

        return $this;
    }



}
