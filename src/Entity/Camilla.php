<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CamillaRepository")
 */
class Camilla
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Habitacion", inversedBy="camillas")
     */
    private $habitacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroCamilla;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingresado", mappedBy="camilla", orphanRemoval=true)
     */
    private $ingresados;

    public function __construct()
    {
        $this->ingresados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHabitacion(): ?Habitacion
    {
        return $this->habitacion;
    }

    public function setHabitacion(?Habitacion $habitacion): self
    {
        $this->habitacion = $habitacion;

        return $this;
    }

    public function getNumeroCamilla(): ?int
    {
        return $this->numeroCamilla;
    }

    public function setNumeroCamilla(int $numeroCamilla): self
    {
        $this->numeroCamilla = $numeroCamilla;

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
     * @return Collection|Ingresado[]
     */
    public function getIngresados(): Collection
    {
        return $this->ingresados;
    }

    public function addIngresado(Ingresado $ingresado): self
    {
        if (!$this->ingresados->contains($ingresado)) {
            $this->ingresados[] = $ingresado;
            $ingresado->setCamilla($this);
        }

        return $this;
    }

    public function removeIngresado(Ingresado $ingresado): self
    {
        if ($this->ingresados->contains($ingresado)) {
            $this->ingresados->removeElement($ingresado);
            // set the owning side to null (unless already changed)
            if ($ingresado->getCamilla() === $this) {
                $ingresado->setCamilla(null);
            }
        }

        return $this;
    }
}
