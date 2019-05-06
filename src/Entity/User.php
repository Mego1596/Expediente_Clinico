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
     * @ORM\Column(type="string", length=255)
     */
    private $Nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Apellidos;

    /**
     * @Assert\Blank
     */
    private $nuevo_password;

    /**
     * @Assert\Blank
     */
    private $repetir_nuevo_password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Especialidad", inversedBy="users")
     */
    private $usuario_especialidades;

    public function __construct()
    {
        $this->usuario_especialidades = new ArrayCollection();
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

    public function getNombres(): ?string
    {
        return $this->Nombres;
    }

    public function setNombres(string $Nombres): self
    {
        $this->Nombres = $Nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): self
    {
        $this->Apellidos = $Apellidos;

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

    /**
     * @return Collection|Especialidad[]
     */
    public function getUsuarioEspecialidades(): Collection
    {
        return $this->usuario_especialidades;
    }

    public function getNuevoPassword(): ?string
    {
        return $this->nuevo_password;
    }

    public function getRepetirNuevoPassword(): ?string
    {
        return $this->repetir_nuevo_password;
    }

    public function addUsuarioEspecialidades(Especialidad $usuarioEspecialidades): self
    {
        if (!$this->usuario_especialidades->contains($usuarioEspecialidades)) {
            $this->usuario_especialidades[] = $usuarioEspecialidades;
        }

        return $this;
    }

    public function removeUsuarioEspecialidades(Especialidad $usuarioEspecialidades): self
    {
        if ($this->usuario_especialidades->contains($usuarioEspecialidades)) {
            $this->usuario_especialidades->removeElement($usuarioEspecialidades);
        }

        return $this;
    }
}
