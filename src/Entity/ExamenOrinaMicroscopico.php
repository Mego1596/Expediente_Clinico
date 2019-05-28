<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenOrinaMicroscopicoRepository")
 */
class ExamenOrinaMicroscopico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenOrinaMicroscopico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="text")
     */
    private $uretral;

    /**
     * @ORM\Column(type="text")
     */
    private $urotelio;

    /**
     * @ORM\Column(type="text")
     */
    private $renal;

    /**
     * @ORM\Column(type="text")
     */
    private $leucocitos;

    /**
     * @ORM\Column(type="text")
     */
    private $piocitos;

    /**
     * @ORM\Column(type="text")
     */
    private $eritrocitos;

    /**
     * @ORM\Column(type="text")
     */
    private $bacteria;

    /**
     * @ORM\Column(type="text")
     */
    private $parasitos;

    /**
     * @ORM\Column(type="text")
     */
    private $funguria;

    /**
     * @ORM\Column(type="text")
     */
    private $filamentoDeMucina;

    /**
     * @ORM\Column(type="text")
     */
    private $proteinaUromocoide;

    /**
     * @ORM\Column(type="text")
     */
    private $cilindros;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creado_en;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $actualizado_en;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamenSolicitado(): ?ExamenSolicitado
    {
        return $this->examen_solicitado;
    }

    public function setExamenSolicitado(ExamenSolicitado $examen_solicitado): self
    {
        $this->examen_solicitado = $examen_solicitado;

        return $this;
    }

    public function getUretral(): ?string
    {
        return $this->uretral;
    }

    public function setUretral(string $uretral): self
    {
        $this->uretral = $uretral;

        return $this;
    }

    public function getUrotelio(): ?string
    {
        return $this->urotelio;
    }

    public function setUrotelio(string $urotelio): self
    {
        $this->urotelio = $urotelio;

        return $this;
    }

    public function getRenal(): ?string
    {
        return $this->renal;
    }

    public function setRenal(string $renal): self
    {
        $this->renal = $renal;

        return $this;
    }

    public function getLeucocitos(): ?string
    {
        return $this->leucocitos;
    }

    public function setLeucocitos(string $leucocitos): self
    {
        $this->leucocitos = $leucocitos;

        return $this;
    }

    public function getPiocitos(): ?string
    {
        return $this->piocitos;
    }

    public function setPiocitos(string $piocitos): self
    {
        $this->piocitos = $piocitos;

        return $this;
    }

    public function getEritrocitos(): ?string
    {
        return $this->eritrocitos;
    }

    public function setEritrocitos(string $eritrocitos): self
    {
        $this->eritrocitos = $eritrocitos;

        return $this;
    }

    public function getBacteria(): ?string
    {
        return $this->bacteria;
    }

    public function setBacteria(string $bacteria): self
    {
        $this->bacteria = $bacteria;

        return $this;
    }

    public function getParasitos(): ?string
    {
        return $this->parasitos;
    }

    public function setParasitos(string $parasitos): self
    {
        $this->parasitos = $parasitos;

        return $this;
    }

    public function getFunguria(): ?string
    {
        return $this->funguria;
    }

    public function setFunguria(string $funguria): self
    {
        $this->funguria = $funguria;

        return $this;
    }

    public function getFilamentoDeMucina(): ?string
    {
        return $this->filamentoDeMucina;
    }

    public function setFilamentoDeMucina(string $filamentoDeMucina): self
    {
        $this->filamentoDeMucina = $filamentoDeMucina;

        return $this;
    }

    public function getProteinaUromocoide(): ?string
    {
        return $this->proteinaUromocoide;
    }

    public function setProteinaUromocoide(string $proteinaUromocoide): self
    {
        $this->proteinaUromocoide = $proteinaUromocoide;

        return $this;
    }

    public function getCilindros(): ?string
    {
        return $this->cilindros;
    }

    public function setCilindros(string $cilindros): self
    {
        $this->cilindros = $cilindros;

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
}
