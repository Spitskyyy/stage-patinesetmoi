<?php

namespace App\Entity;

use App\Repository\VoilageRideauxDoublesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoilageRideauxDoublesRepository::class)]
class VoilageRideauxDoubles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usagetxt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?float $largeur = null;

    #[ORM\Column(nullable: true)]
    private ?float $hauteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $doublure = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tissu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $temps = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsagetxt(): ?string
    {
        return $this->usagetxt;
    }

    public function setUsagetxt(?string $usagetxt): static
    {
        $this->usagetxt = $usagetxt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(?float $largeur): static
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(?float $hauteur): static
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getDoublure(): ?string
    {
        return $this->doublure;
    }

    public function setDoublure(?string $doublure): static
    {
        $this->doublure = $doublure;

        return $this;
    }

    public function getTissu(): ?string
    {
        return $this->tissu;
    }

    public function setTissu(?string $tissu): static
    {
        $this->tissu = $tissu;

        return $this;
    }

    public function getFinition(): ?string
    {
        return $this->finition;
    }

    public function setFinition(?string $finition): static
    {
        $this->finition = $finition;

        return $this;
    }

    public function getTemps(): ?string
    {
        return $this->temps;
    }

    public function setTemps(?string $temps): static
    {
        $this->temps = $temps;

        return $this;
    }
}
