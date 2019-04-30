<?php

namespace App\Entity;
use Symfony\Component\Security\Core\Role\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolRepository")
 */
class Rol
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
    private $nombreRol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="rol", orphanRemoval=true)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PermisosPorRol", mappedBy="rol")
     */
    private $permisosPorRoles;

    public function __construct()
    {
        $this->usuario = new ArrayCollection();
        $this->permisosPorRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
    }

    public function getNombreRol(): ?string
    {
        return $this->nombreRol;
    }

    public function setNombreRol(string $nombreRol): self
    {
        $this->nombreRol = $nombreRol;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
     * @return Collection|User[]
     */
    public function getUsuario(): Collection
    {
        return $this->usuario;
    }

    public function addUsuario(User $usuario): self
    {
        if (!$this->usuario->contains($usuario)) {
            $this->usuario[] = $usuario;
            $usuario->setRol($this);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): self
    {
        if ($this->usuario->contains($usuario)) {
            $this->usuario->removeElement($usuario);
            // set the owning side to null (unless already changed)
            if ($usuario->getRol() === $this) {
                $usuario->setRol(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PermisosPorRol[]
     */
    public function getPermisosPorRoles(): Collection
    {
        return $this->permisosPorRoles;
    }

    public function addPermisosPorRol(PermisosPorRol $permisosPorRol): self
    {
        if (!$this->permisosPorRoles->contains($permisosPorRol)) {
            $this->permisosPorRoles[] = $permisosPorRol;
            $permisosPorRol->setRol($this);
        }

        return $this;
    }

    public function removePermisosPorRol(PermisosPorRol $permisosPorRol): self
    {
        if ($this->permisosPorRoles->contains($permisosPorRol)) {
            $this->permisosPorRoles->removeElement($permisosPorRol);
            // set the owning side to null (unless already changed)
            if ($permisosPorRol->getRol() === $this) {
                $permisosPorRol->setRol(null);
            }
        }

        return $this;
    }
}
