<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenSolicitadoRepository")
 */
class ExamenSolicitado
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
    private $tipoExamen;

    /**
     * @ORM\Column(type="text")
     */
    private $categoria;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Anexo", mappedBy="examen_solicitado", orphanRemoval=true)
     */
    private $anexos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenOrinaCristaluria", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenOrinaCristaluria;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenOrinaQuimico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenOrinaQuimico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenOrinaMacroscopico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenOrinaMacroscopico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenOrinaMicroscopico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenOrinaMicroscopico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenHecesMacroscopico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenHecesMacroscopico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenHecesQuimico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenHecesQuimico;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenHecesMicroscopico", mappedBy="examen_solicitado", cascade={"persist", "remove"})
     */
    private $examenHecesMicroscopico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cita", inversedBy="examenes")
     */
    private $cita;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamenHematologico", mappedBy="examen_solicitado")
     */
    private $examenHematologicos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamenQuimicaSanguinea", mappedBy="examen_solicitado")
     */
    private $examenQuimicaSanguineas;

    public function __construct()
    {
        $this->anexos = new ArrayCollection();
        $this->examenHematologicos = new ArrayCollection();
        $this->examenQuimicaSanguineas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoExamen(): ?string
    {
        return $this->tipoExamen;
    }

    public function setTipoExamen(string $tipoExamen): self
    {
        $this->tipoExamen = $tipoExamen;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

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
     * @return Collection|Anexo[]
     */
    public function getAnexos(): Collection
    {
        return $this->anexos;
    }

    public function addAnexo(Anexo $anexo): self
    {
        if (!$this->anexos->contains($anexo)) {
            $this->anexos[] = $anexo;
            $anexo->setExamenSolicitado($this);
        }

        return $this;
    }

    public function removeAnexo(Anexo $anexo): self
    {
        if ($this->anexos->contains($anexo)) {
            $this->anexos->removeElement($anexo);
            // set the owning side to null (unless already changed)
            if ($anexo->getExamenSolicitado() === $this) {
                $anexo->setExamenSolicitado(null);
            }
        }

        return $this;
    }

    public function getExamenHematologico(): ?ExamenHematologico
    {
        return $this->examenHematologico;
    }

    public function setExamenHematologico(ExamenHematologico $examenHematologico): self
    {
        $this->examenHematologico = $examenHematologico;

        // set the owning side of the relation if necessary
        if ($this !== $examenHematologico->getExamenSolicitado()) {
            $examenHematologico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenOrinaCristaluria(): ?ExamenOrinaCristaluria
    {
        return $this->examenOrinaCristaluria;
    }

    public function setExamenOrinaCristaluria(ExamenOrinaCristaluria $examenOrinaCristaluria): self
    {
        $this->examenOrinaCristaluria = $examenOrinaCristaluria;

        // set the owning side of the relation if necessary
        if ($this !== $examenOrinaCristaluria->getExamenSolicitado()) {
            $examenOrinaCristaluria->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenOrinaQuimico(): ?ExamenOrinaQuimico
    {
        return $this->examenOrinaQuimico;
    }

    public function setExamenOrinaQuimico(ExamenOrinaQuimico $examenOrinaQuimico): self
    {
        $this->examenOrinaQuimico = $examenOrinaQuimico;

        // set the owning side of the relation if necessary
        if ($this !== $examenOrinaQuimico->getExamenSolicitado()) {
            $examenOrinaQuimico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenOrinaMacroscopico(): ?ExamenOrinaMacroscopico
    {
        return $this->examenOrinaMacroscopico;
    }

    public function setExamenOrinaMacroscopico(ExamenOrinaMacroscopico $examenOrinaMacroscopico): self
    {
        $this->examenOrinaMacroscopico = $examenOrinaMacroscopico;

        // set the owning side of the relation if necessary
        if ($this !== $examenOrinaMacroscopico->getExamenSolicitado()) {
            $examenOrinaMacroscopico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenOrinaMicroscopico(): ?ExamenOrinaMicroscopico
    {
        return $this->examenOrinaMicroscopico;
    }

    public function setExamenOrinaMicroscopico(ExamenOrinaMicroscopico $examenOrinaMicroscopico): self
    {
        $this->examenOrinaMicroscopico = $examenOrinaMicroscopico;

        // set the owning side of the relation if necessary
        if ($this !== $examenOrinaMicroscopico->getExamenSolicitado()) {
            $examenOrinaMicroscopico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenHecesMacroscopico(): ?ExamenHecesMacroscopico
    {
        return $this->examenHecesMacroscopico;
    }

    public function setExamenHecesMacroscopico(ExamenHecesMacroscopico $examenHecesMacroscopico): self
    {
        $this->examenHecesMacroscopico = $examenHecesMacroscopico;

        // set the owning side of the relation if necessary
        if ($this !== $examenHecesMacroscopico->getExamenSolicitado()) {
            $examenHecesMacroscopico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenHecesQuimico(): ?ExamenHecesQuimico
    {
        return $this->examenHecesQuimico;
    }

    public function setExamenHecesQuimico(ExamenHecesQuimico $examenHecesQuimico): self
    {
        $this->examenHecesQuimico = $examenHecesQuimico;

        // set the owning side of the relation if necessary
        if ($this !== $examenHecesQuimico->getExamenSolicitado()) {
            $examenHecesQuimico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenHecesMicroscopico(): ?ExamenHecesMicroscopico
    {
        return $this->examenHecesMicroscopico;
    }

    public function setExamenHecesMicroscopico(ExamenHecesMicroscopico $examenHecesMicroscopico): self
    {
        $this->examenHecesMicroscopico = $examenHecesMicroscopico;

        // set the owning side of the relation if necessary
        if ($this !== $examenHecesMicroscopico->getExamenSolicitado()) {
            $examenHecesMicroscopico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getExamenQuimicaSanguinea(): ?ExamenQuimicaSanguinea
    {
        return $this->examenQuimicaSanguinea;
    }

    public function setExamenQuimicaSanguinea(ExamenQuimicaSanguinea $examenQuimicaSanguinea): self
    {
        $this->examenQuimicaSanguinea = $examenQuimicaSanguinea;

        // set the owning side of the relation if necessary
        if ($this !== $examenQuimicaSanguinea->getExamenSolicitado()) {
            $examenQuimicaSanguinea->setExamenSolicitado($this);
        }

        return $this;
    }

    public function getCita(): ?Cita
    {
        return $this->cita;
    }

    public function setCita(?Cita $cita): self
    {
        $this->cita = $cita;

        return $this;
    }

    /**
     * @return Collection|ExamenHematologico[]
     */
    public function getExamenHematologicos(): Collection
    {
        return $this->examenHematologicos;
    }

    public function addExamenHematologico(ExamenHematologico $examenHematologico): self
    {
        if (!$this->examenHematologicos->contains($examenHematologico)) {
            $this->examenHematologicos[] = $examenHematologico;
            $examenHematologico->setExamenSolicitado($this);
        }

        return $this;
    }

    public function removeExamenHematologico(ExamenHematologico $examenHematologico): self
    {
        if ($this->examenHematologicos->contains($examenHematologico)) {
            $this->examenHematologicos->removeElement($examenHematologico);
            // set the owning side to null (unless already changed)
            if ($examenHematologico->getExamenSolicitado() === $this) {
                $examenHematologico->setExamenSolicitado(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExamenQuimicaSanguinea[]
     */
    public function getExamenQuimicaSanguineas(): Collection
    {
        return $this->examenQuimicaSanguineas;
    }

    public function addExamenQuimicaSanguinea(ExamenQuimicaSanguinea $examenQuimicaSanguinea): self
    {
        if (!$this->examenQuimicaSanguineas->contains($examenQuimicaSanguinea)) {
            $this->examenQuimicaSanguineas[] = $examenQuimicaSanguinea;
            $examenQuimicaSanguinea->setExamenSolicitado($this);
        }

        return $this;
    }

    public function removeExamenQuimicaSanguinea(ExamenQuimicaSanguinea $examenQuimicaSanguinea): self
    {
        if ($this->examenQuimicaSanguineas->contains($examenQuimicaSanguinea)) {
            $this->examenQuimicaSanguineas->removeElement($examenQuimicaSanguinea);
            // set the owning side to null (unless already changed)
            if ($examenQuimicaSanguinea->getExamenSolicitado() === $this) {
                $examenQuimicaSanguinea->setExamenSolicitado(null);
            }
        }

        return $this;
    }
}
