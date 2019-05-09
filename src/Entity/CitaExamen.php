<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CitaExamenRepository")
 */
class CitaExamen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cita", inversedBy="citaExamen", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cita;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaExamen;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamenSolicitado", mappedBy="cita_examen", orphanRemoval=true)
     */
    private $examenSolicitados;

    public function __construct()
    {
        $this->examenSolicitados = new ArrayCollection();
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

    public function getFechaExamen(): ?\DateTimeInterface
    {
        return $this->fechaExamen;
    }

    public function setFechaExamen(\DateTimeInterface $fechaExamen): self
    {
        $this->fechaExamen = $fechaExamen;

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
     * @return Collection|ExamenSolicitado[]
     */
    public function getExamenSolicitados(): Collection
    {
        return $this->examenSolicitados;
    }

    public function addExamenSolicitado(ExamenSolicitado $examenSolicitado): self
    {
        if (!$this->examenSolicitados->contains($examenSolicitado)) {
            $this->examenSolicitados[] = $examenSolicitado;
            $examenSolicitado->setCitaExamen($this);
        }

        return $this;
    }

    public function removeExamenSolicitado(ExamenSolicitado $examenSolicitado): self
    {
        if ($this->examenSolicitados->contains($examenSolicitado)) {
            $this->examenSolicitados->removeElement($examenSolicitado);
            // set the owning side to null (unless already changed)
            if ($examenSolicitado->getCitaExamen() === $this) {
                $examenSolicitado->setCitaExamen(null);
            }
        }

        return $this;
    }
}
