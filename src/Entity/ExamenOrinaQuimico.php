<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamenOrinaQuimicoRepository")
 */
class ExamenOrinaQuimico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ExamenSolicitado", inversedBy="examenOrinaQuimico", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen_solicitado;

    /**
     * @ORM\Column(type="float")
     */
    private $densidad;

    /**
     * @ORM\Column(type="float")
     */
    private $ph;

    /**
     * @ORM\Column(type="text")
     */
    private $glucosa;

    /**
     * @ORM\Column(type="text")
     */
    private $proteinas;

    /**
     * @ORM\Column(type="text")
     */
    private $hemoglobina;

    /**
     * @ORM\Column(type="text")
     */
    private $cuerpoCetonico;

    /**
     * @ORM\Column(type="text")
     */
    private $pigmentoBiliar;

    /**
     * @ORM\Column(type="text")
     */
    private $urobilinogeno;

    /**
     * @ORM\Column(type="text")
     */
    private $nitritos;

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

    public function getDensidad(): ?float
    {
        return $this->densidad;
    }

    public function setDensidad(float $densidad): self
    {
        $this->densidad = $densidad;

        return $this;
    }

    public function getPh(): ?float
    {
        return $this->ph;
    }

    public function setPh(float $ph): self
    {
        $this->ph = $ph;

        return $this;
    }

    public function getGlucosa(): ?string
    {
        return $this->glucosa;
    }

    public function setGlucosa(string $glucosa): self
    {
        $this->glucosa = $glucosa;

        return $this;
    }

    public function getProteinas(): ?string
    {
        return $this->proteinas;
    }

    public function setProteinas(string $proteinas): self
    {
        $this->proteinas = $proteinas;

        return $this;
    }

    public function getHemoglobina(): ?string
    {
        return $this->hemoglobina;
    }

    public function setHemoglobina(string $hemoglobina): self
    {
        $this->hemoglobina = $hemoglobina;

        return $this;
    }

    public function getCuerpoCetonico(): ?string
    {
        return $this->cuerpoCetonico;
    }

    public function setCuerpoCetonico(string $cuerpoCetonico): self
    {
        $this->cuerpoCetonico = $cuerpoCetonico;

        return $this;
    }

    public function getPigmentoBiliar(): ?string
    {
        return $this->pigmentoBiliar;
    }

    public function setPigmentoBiliar(string $pigmentoBiliar): self
    {
        $this->pigmentoBiliar = $pigmentoBiliar;

        return $this;
    }

    public function getUrobilinogeno(): ?string
    {
        return $this->urobilinogeno;
    }

    public function setUrobilinogeno(string $urobilinogeno): self
    {
        $this->urobilinogeno = $urobilinogeno;

        return $this;
    }

    public function getNitritos(): ?string
    {
        return $this->nitritos;
    }

    public function setNitritos(string $nitritos): self
    {
        $this->nitritos = $nitritos;

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
