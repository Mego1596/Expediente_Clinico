<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\FamiliarRepository")
 */
class Familiar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="text")
     * @Assert\Regex( 
     * pattern="/^[0-9]{8}$/",
     * message="El campo es un numero telefónico, digite numeros unicamente.")
     */
    private $telefono;

    /**
     * @ORM\Column(type="text")
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
     * @ORM\OneToMany(targetEntity="App\Entity\FamiliaresExpediente", mappedBy="familiar", orphanRemoval=true)
     */
    private $familiaresExpedientes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistorialFamiliar", mappedBy="familiar", orphanRemoval=true)
     */
    private $historialFamiliars;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $primerNombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $segundoNombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $segundoApellido;

    public function __construct()
    {
        $this->familiaresExpedientes = new ArrayCollection();
        $this->historialFamiliars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

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
     * @return Collection|FamiliaresExpediente[]
     */
    public function getFamiliaresExpedientes(): Collection
    {
        return $this->familiaresExpedientes;
    }

    public function addFamiliaresExpediente(FamiliaresExpediente $familiaresExpediente): self
    {
        if (!$this->familiaresExpedientes->contains($familiaresExpediente)) {
            $this->familiaresExpedientes[] = $familiaresExpediente;
            $familiaresExpediente->setFamiliar($this);
        }

        return $this;
    }

    public function removeFamiliaresExpediente(FamiliaresExpediente $familiaresExpediente): self
    {
        if ($this->familiaresExpedientes->contains($familiaresExpediente)) {
            $this->familiaresExpedientes->removeElement($familiaresExpediente);
            // set the owning side to null (unless already changed)
            if ($familiaresExpediente->getFamiliar() === $this) {
                $familiaresExpediente->setFamiliar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistorialFamiliar[]
     */
    public function getHistorialFamiliares(): Collection
    {
        return $this->historialFamiliars;
    }

    public function addHistorialFamiliar(HistorialFamiliar $historialFamiliar): self
    {
        if (!$this->historialFamiliars->contains($historialFamiliar)) {
            $this->historialFamiliars[] = $historialFamiliar;
            $historialFamiliar->setFamiliar($this);
        }

        return $this;
    }

    public function removeHistorialFamiliar(HistorialFamiliar $historialFamiliar): self
    {
        if ($this->historialFamiliars->contains($historialFamiliar)) {
            $this->historialFamiliars->removeElement($historialFamiliar);
            // set the owning side to null (unless already changed)
            if ($historialFamiliar->getFamiliar() === $this) {
                $historialFamiliar->setFamiliar(null);
            }
        }

        return $this;
    }

    public function getPrimerNombre(): ?string
    {
        return $this->primerNombre;
    }

    public function setPrimerNombre(string $primerNombre): self
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    public function getSegundoNombre(): ?string
    {
        return $this->segundoNombre;
    }

    public function setSegundoNombre(?string $segundoNombre): self
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    public function getPrimerApellido(): ?string
    {
        return $this->primerApellido;
    }

    public function setPrimerApellido(string $primerApellido): self
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido;
    }

    public function setSegundoApellido(?string $segundoApellido): self
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }
}
