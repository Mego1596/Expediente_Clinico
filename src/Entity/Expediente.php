<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpedienteRepository")
 */
class Expediente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="expediente", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;


    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @ORM\Column(type="text")
     */
    private $numeroExpediente;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genero", inversedBy="expedientes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genero;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="text")
     */
    private $direccion;

    /**
     * @ORM\Column(type="text")
     */
    private $telefono;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $apellidoCasada;

    /**
     * @ORM\Column(type="text")
     */
    private $estadoCivil;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FamiliaresExpediente", mappedBy="expediente", orphanRemoval=true)
     */
    private $familiaresExpedientes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingresado", mappedBy="expediente", orphanRemoval=true)
     */
    private $ingresados;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistorialPropio", mappedBy="expediente", orphanRemoval=true)
     */
    private $historialPropios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cita", mappedBy="expediente", orphanRemoval=true)
     */
    private $citas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    public function __construct()
    {
        $this->familiaresExpedientes = new ArrayCollection();
        $this->ingresados = new ArrayCollection();
        $this->historialPropios = new ArrayCollection();
        $this->citas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNumeroExpediente(): ?string
    {
        return $this->numeroExpediente;
    }

    public function setNumeroExpediente(string $numeroExpediente): self
    {
        $this->numeroExpediente = $numeroExpediente;

        return $this;
    }

    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(?Genero $genero): self
    {
        $this->genero = $genero;

        return $this;
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

    public function getApellidoCasada(): ?string
    {
        return $this->apellidoCasada;
    }

    public function setApellidoCasada(?string $apellidoCasada): self
    {
        $this->apellidoCasada = $apellidoCasada;

        return $this;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(string $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

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
            $familiaresExpediente->setExpediente($this);
        }

        return $this;
    }

    public function removeFamiliaresExpediente(FamiliaresExpediente $familiaresExpediente): self
    {
        if ($this->familiaresExpedientes->contains($familiaresExpediente)) {
            $this->familiaresExpedientes->removeElement($familiaresExpediente);
            // set the owning side to null (unless already changed)
            if ($familiaresExpediente->getExpediente() === $this) {
                $familiaresExpediente->setExpediente(null);
            }
        }

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
            $ingresado->setExpediente($this);
        }

        return $this;
    }

    public function removeIngresado(Ingresado $ingresado): self
    {
        if ($this->ingresados->contains($ingresado)) {
            $this->ingresados->removeElement($ingresado);
            // set the owning side to null (unless already changed)
            if ($ingresado->getExpediente() === $this) {
                $ingresado->setExpediente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistorialPropio[]
     */
    public function getHistorialPropios(): Collection
    {
        return $this->historialPropios;
    }

    public function addHistorialPropio(HistorialPropio $historialPropio): self
    {
        if (!$this->historialPropios->contains($historialPropio)) {
            $this->historialPropios[] = $historialPropio;
            $historialPropio->setExpediente($this);
        }

        return $this;
    }

    public function removeHistorialPropio(HistorialPropio $historialPropio): self
    {
        if ($this->historialPropios->contains($historialPropio)) {
            $this->historialPropios->removeElement($historialPropio);
            // set the owning side to null (unless already changed)
            if ($historialPropio->getExpediente() === $this) {
                $historialPropio->setExpediente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cita[]
     */
    public function getCitas(): Collection
    {
        return $this->citas;
    }

    public function addCita(Cita $cita): self
    {
        if (!$this->citas->contains($cita)) {
            $this->citas[] = $cita;
            $cita->setExpediente($this);
        }

        return $this;
    }

    public function removeCita(Cita $cita): self
    {
        if ($this->citas->contains($cita)) {
            $this->citas->removeElement($cita);
            // set the owning side to null (unless already changed)
            if ($cita->getExpediente() === $this) {
                $cita->setExpediente(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function getHabilitado(): ?bool
    {
        return $this->habilitado;
    }

    public function setHabilitado(bool $habilitado): self
    {
        $this->habilitado = $habilitado;

        return $this;
    }
}
