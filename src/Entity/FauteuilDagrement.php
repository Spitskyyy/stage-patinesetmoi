<?php

namespace App\Entity;

use App\Repository\FauteuilDagrementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FauteuilDagrementRepository::class)]
#[ORM\Table(name: '`tbl_fauteuildagrement`')]
class FauteuilDagrement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usagetxt = null;

    #[ORM\Column(nullable: true)]
    private ?float $largeur = null;

    #[ORM\Column(nullable: true)]
    private ?float $profondeur = null;

    #[ORM\Column(nullable: true)]
    private ?float $hauteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recouverture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $materiaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tissu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $temps = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(?float $largeur): static
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getProfondeur(): ?float
    {
        return $this->profondeur;
    }

    public function setProfondeur(?float $profondeur): static
    {
        $this->profondeur = $profondeur;

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

    public function getRecouverture(): ?string
    {
        return $this->recouverture;
    }

    public function setRecouverture(?string $recouverture): static
    {
        $this->recouverture = $recouverture;

        return $this;
    }

    public function getMateriaux(): ?string
    {
        return $this->materiaux;
    }

    public function setMateriaux(?string $materiaux): static
    {
        $this->materiaux = $materiaux;

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

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
}
