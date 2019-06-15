<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodigoInternacionalRepository")
 */
class CodigoInternacional
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $dec10;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $grp10;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HistoriaMedica", mappedBy="id10")
     */
    private $historiaMedicas;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $id10;

    public function __construct()
    {
        $this->historiaMedicas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDec10(): ?string
    {
        return $this->dec10;
    }

    public function setDec10(?string $dec10): self
    {
        $this->dec10 = $dec10;

        return $this;
    }

    public function getGrp10(): ?string
    {
        return $this->grp10;
    }

    public function setGrp10(?string $grp10): self
    {
        $this->grp10 = $grp10;

        return $this;
    }

    /**
     * @return Collection|HistoriaMedica[]
     */
    public function getHistoriaMedicas(): Collection
    {
        return $this->historiaMedicas;
    }

    public function addHistoriaMedica(HistoriaMedica $historiaMedica): self
    {
        if (!$this->historiaMedicas->contains($historiaMedica)) {
            $this->historiaMedicas[] = $historiaMedica;
            $historiaMedica->addId10($this);
        }

        return $this;
    }

    public function removeHistoriaMedica(HistoriaMedica $historiaMedica): self
    {
        if ($this->historiaMedicas->contains($historiaMedica)) {
            $this->historiaMedicas->removeElement($historiaMedica);
            $historiaMedica->removeId10($this);
        }

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

    public function getId10(): ?string
    {
        return $this->id10;
    }

    public function setId10(string $id10): self
    {
        $this->id10 = $id10;

        return $this;
    }
}
