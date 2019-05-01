<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Rol", inversedBy="usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clinica", inversedBy="usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hospital;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsuarioEspecialidad", mappedBy="usuario", orphanRemoval=true)
     */
    private $usuarioEspecialidades;

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


    public function getHospital(): ?Clinica
    {
        return $this->hospital;
    }

    public function setHospital(?Clinica $hospital): self
    {
        $this->hospital = $hospital;

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
    {

        return ['ROLE_USER'];
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
}
