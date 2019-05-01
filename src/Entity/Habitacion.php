<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HabitacionRepository")
 */
class Habitacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sala", inversedBy="habitaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sala;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoHabitacion", inversedBy="habitaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoHabitacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroHabitacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Camilla", mappedBy="habitacion")
     */
    private $camillas;

    public function __construct()
    {
        $this->camillas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSala(): ?Sala
    {
        return $this->sala;
    }

    public function setSala(?Sala $sala): self
    {
        $this->sala = $sala;

        return $this;
    }

    public function getTipoHabitacion(): ?TipoHabitacion
    {
        return $this->tipoHabitacion;
    }

    public function setTipoHabitacion(?TipoHabitacion $tipoHabitacion): self
    {
        $this->tipoHabitacion = $tipoHabitacion;

        return $this;
    }

    public function getNumeroHabitacion(): ?int
    {
        return $this->numeroHabitacion;
    }

    public function setNumeroHabitacion(int $numeroHabitacion): self
    {
        $this->numeroHabitacion = $numeroHabitacion;

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
     * @return Collection|Camilla[]
     */
    public function getCamillas(): Collection
    {
        return $this->camillas;
    }

    public function addCamilla(Camilla $camilla): self
    {
        if (!$this->camillas->contains($camilla)) {
            $this->camillas[] = $camilla;
            $camilla->setHabitacion($this);
        }

        return $this;
    }

    public function removeCamilla(Camilla $camilla): self
    {
        if ($this->camillas->contains($camilla)) {
            $this->camillas->removeElement($camilla);
            // set the owning side to null (unless already changed)
            if ($camilla->getHabitacion() === $this) {
                $camilla->setHabitacion(null);
            }
        }

        return $this;
    }
}
