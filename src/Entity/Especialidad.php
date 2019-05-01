<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EspecialidadRepository")
 */
class Especialidad
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
    private $nombreEspecialidad;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsuarioEspecialidad", mappedBy="especialidad", orphanRemoval=true)
     */
    private $usuarioEspecialidades;

    public function __construct()
    {
        $this->usuarioEspecialidades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEspecialidad(): ?string
    {
        return $this->nombreEspecialidad;
    }

    public function setNombreEspecialidad(string $nombreEspecialidad): self
    {
        $this->nombreEspecialidad = $nombreEspecialidad;

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
     * @return Collection|UsuarioEspecialidad[]
     */
    public function getUsuarioEspecialidades(): Collection
    {
        return $this->usuarioEspecialidades;
    }

    public function addUsuarioEspecialidade(UsuarioEspecialidad $usuarioEspecialidade): self
    {
        if (!$this->usuarioEspecialidades->contains($usuarioEspecialidade)) {
            $this->usuarioEspecialidades[] = $usuarioEspecialidade;
            $usuarioEspecialidade->setEspecialidad($this);
        }

        return $this;
    }

    public function removeUsuarioEspecialidade(UsuarioEspecialidad $usuarioEspecialidade): self
    {
        if ($this->usuarioEspecialidades->contains($usuarioEspecialidade)) {
            $this->usuarioEspecialidades->removeElement($usuarioEspecialidade);
            // set the owning side to null (unless already changed)
            if ($usuarioEspecialidade->getEspecialidad() === $this) {
                $usuarioEspecialidade->setEspecialidad(null);
            }
        }

        return $this;
    }
}
