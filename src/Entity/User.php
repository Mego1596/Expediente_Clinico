<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface,\Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;


    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Rol",inversedBy="usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clinica", inversedBy="usuario")
     * @ORM\JoinColumn(name="clinica_id", referencedColumnName="id",nullable=true)
     */
    private $clinica;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Expediente", mappedBy="usuario", cascade={"persist", "remove"})
     */
    private $expediente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cita", mappedBy="usuario", orphanRemoval=true)
     */
    private $citas;

    /**
     * @Assert\Blank
     */
    private $nuevo_password;

    /**
     * @Assert\Blank
     */
    private $repetir_nuevo_password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $emergencia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Especialidad", inversedBy="users")
     */
    private $usuario_especialidades;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $planta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingresado", mappedBy="usuario")
     */
    private $ingresados;


    public function __construct()
    {
        $this->ingresados = new ArrayCollection();
        $this->citas = new ArrayCollection();
    }


    public function getClinica():?Clinica
    {
        return $this->clinica;
    }

    public function setClinica(?Clinica $clinica): self
    {
        $this->clinica = $clinica;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {   $permisos= array();
        $array = $this->getRol()->getPermisos()->toArray();
        foreach ($array as $value) {
            $permisos[] = $value->getPermiso().$value->getNombreTabla();
        }
        $permisos[] = "ROLE_PERMISSION_USER";
        return $permisos;
        
    }

    public function setRoles(array $roles): self
    {
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getExpediente(): ?Expediente
    {
        return $this->expediente;
    }

    public function setExpediente(Expediente $expediente): self
    {
        $this->expediente = $expediente;

        // set the owning side of the relation if necessary
        if ($this !== $expediente->getUsuario()) {
            $expediente->setUsuario($this);
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
            $cita->setUsuario($this);
        }

        return $this;
    }

    public function removeCita(Cita $cita): self
    {
        if ($this->citas->contains($cita)) {
            $this->citas->removeElement($cita);
            // set the owning side to null (unless already changed)
            if ($cita->getUsuario() === $this) {
                $cita->setUsuario(null);
            }
        }

        return $this;
    }

     /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }


    public function getNuevoPassword(): ?string
    {
        return $this->nuevo_password;
    }

    public function getRepetirNuevoPassword(): ?string
    {
        return $this->repetir_nuevo_password;
    }


    public function tieneRol(string $rol): bool
    {
        return in_array($rol, $this->getRoles());
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getEmergencia(): ?bool
    {
        return $this->emergencia;
    }

    public function setEmergencia(?bool $emergencia): self
    {
        $this->emergencia = $emergencia;

        return $this;
    }

    public function getUsuarioEspecialidades(): ?Especialidad
    {
        return $this->usuario_especialidades;
    }

    public function setUsuarioEspecialidades(?Especialidad $usuario_especialidades): self
    {
        $this->usuario_especialidades = $usuario_especialidades;

        return $this;
    }

    public function getPlanta(): ?bool
    {
        return $this->planta;
    }

    public function setPlanta(?bool $planta): self
    {
        $this->planta = $planta;

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
            $ingresado->setUsuario($this);
        }

        return $this;
    }

    public function removeIngresado(Ingresado $ingresado): self
    {
        if ($this->ingresados->contains($ingresado)) {
            $this->ingresados->removeElement($ingresado);
            // set the owning side to null (unless already changed)
            if ($ingresado->getUsuario() === $this) {
                $ingresado->setUsuario(null);
            }
        }

        return $this;
    }
}
