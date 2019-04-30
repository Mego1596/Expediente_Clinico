<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermisosPorRolRepository")
 */
class PermisosPorRol
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rol", inversedBy="permisosPorRols")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $permiso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_tabla;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getPermiso(): ?string
    {
        return $this->permiso;
    }

    public function setPermiso(string $permiso): self
    {
        $this->permiso = $permiso;

        return $this;
    }

    public function getNombreTabla(): ?string
    {
        return $this->nombre_tabla;
    }

    public function setNombreTabla(string $nombre_tabla): self
    {
        $this->nombre_tabla = $nombre_tabla;

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
}
