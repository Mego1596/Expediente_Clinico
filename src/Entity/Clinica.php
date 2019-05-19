<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClinicaRepository")
 */
class Clinica
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
    private $nombreClinica;

    /**
     * @ORM\Column(type="text")
     */
    private $direccion;

    /**
     * @ORM\Column(type="text")
     */
    private $telefono;

    /**
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sala", mappedBy="clinica", orphanRemoval=true)
     */
    private $salas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="clinica", orphanRemoval=true)
     */
    private $usuario;

    public function __construct()
    {
        $this->salas = new ArrayCollection();
        $this->usuario = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreClinica(): ?string
    {
        return $this->nombreClinica;
    }

    public function setNombreClinica(string $nombreClinica): self
    {
        $this->nombreClinica = $nombreClinica;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
     * @return Collection|Sala[]
     */
    public function getSalas(): Collection
    {
        return $this->salas;
    }

    public function addSala(Sala $sala): self
    {
        if (!$this->salas->contains($sala)) {
            $this->salas[] = $sala;
            $sala->setIdHospital($this);
        }

        return $this;
    }

    public function removeSala(Sala $sala): self
    {
        if ($this->salas->contains($sala)) {
            $this->salas->removeElement($sala);
            // set the owning side to null (unless already changed)
            if ($sala->getIdHospital() === $this) {
                $sala->setIdHospital(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuario;
    }

    public function addUsuario(User $usuario): self
    {
        if (!$this->usuario->contains($usuario)) {
            $this->usuario[] = $usuario;
            $usuario->setHospital($this);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): self
    {
        if ($this->usuario->contains($usuario)) {
            $this->usuario->removeElement($usuario);
            // set the owning side to null (unless already changed)
            if ($usuario->getHospital() === $this) {
                $usuario->setHospital(null);
            }
        }

        return $this;
    }
}
