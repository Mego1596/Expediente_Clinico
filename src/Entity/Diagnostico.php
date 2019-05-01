<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiagnosticoRepository")
 */
class Diagnostico
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
     * @ORM\OneToOne(targetEntity="App\Entity\HistoriaMedica", mappedBy="diagnostico", cascade={"persist", "remove"})
     */
    private $historiaMedica;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlanTratamiento", mappedBy="diagnostico", orphanRemoval=true)
     */
    private $planTratamientos;

    public function __construct()
    {
        $this->planTratamientos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHistoriaMedica(): ?HistoriaMedica
    {
        return $this->historiaMedica;
    }

    public function setHistoriaMedica(HistoriaMedica $historiaMedica): self
    {
        $this->historiaMedica = $historiaMedica;

        // set the owning side of the relation if necessary
        if ($this !== $historiaMedica->getDiagnostico()) {
            $historiaMedica->setDiagnostico($this);
        }

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
            $planTratamiento->setDiagnostico($this);
        }

        return $this;
    }

    public function removePlanTratamiento(PlanTratamiento $planTratamiento): self
    {
        if ($this->planTratamientos->contains($planTratamiento)) {
            $this->planTratamientos->removeElement($planTratamiento);
            // set the owning side to null (unless already changed)
            if ($planTratamiento->getDiagnostico() === $this) {
                $planTratamiento->setDiagnostico(null);
            }
        }

        return $this;
    }
}
