<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriaMedicaRepository")
 */
class HistoriaMedica
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cita", inversedBy="historiaMedica")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cita;

    /**
     * @ORM\Column(type="text")
     */
    private $consultaPor;

    /**
     * @ORM\Column(type="text")
     */
    private $signos;

    /**
     * @ORM\Column(type="text")
     */
    private $sintomas;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlanTratamiento", mappedBy="historiaMedica", orphanRemoval=true)
     */
    private $planTratamientos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CodigoInternacional", inversedBy="historiaMedicas")
     */
    private $id10;

    public function __construct()
    {
        $this->planTratamientos = new ArrayCollection();
        $this->id10 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCita(): ?Cita
    {
        return $this->cita;
    }

    public function setCita(Cita $cita): self
    {
        $this->cita = $cita;

        return $this;
    }

    public function getConsultaPor(): ?string
    {
        return $this->consultaPor;
    }

    public function setConsultaPor(string $consultaPor): self
    {
        $this->consultaPor = $consultaPor;

        return $this;
    }

    public function getSignos(): ?string
    {
        return $this->signos;
    }

    public function setSignos(string $signos): self
    {
        $this->signos = $signos;

        return $this;
    }

    public function getSintomas(): ?string
    {
        return $this->sintomas;
    }

    public function setSintomas(string $sintomas): self
    {
        $this->sintomas = $sintomas;

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
     * @return Collection|PlanTratamiento[]
     */
    public function getPlanTratamientos(): Collection
    {
        return $this->planTratamientos;
    }

    public function addPlanTratamiento(PlanTratamiento $planTratamiento): self
    {
        if (!$this->planTratamientos->contains($planTratamiento)) {
            $this->planTratamientos[] = $planTratamiento;
            $planTratamiento->setHistoriaMedica($this);
        }

        return $this;
    }

    public function removePlanTratamiento(PlanTratamiento $planTratamiento): self
    {
        if ($this->planTratamientos->contains($planTratamiento)) {
            $this->planTratamientos->removeElement($planTratamiento);
            // set the owning side to null (unless already changed)
            if ($planTratamiento->getHistoriaMedica() === $this) {
                $planTratamiento->setHistoriaMedica(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CodigoInternacional[]
     */
    public function getId10(): Collection
    {
        return $this->id10;
    }

    public function addId10(CodigoInternacional $id10): self
    {
        if (!$this->id10->contains($id10)) {
            $this->id10[] = $id10;
        }

        return $this;
    }

    public function removeId10(CodigoInternacional $id10): self
    {
        if ($this->id10->contains($id10)) {
            $this->id10->removeElement($id10);
        }

        return $this;
    }

    
}
