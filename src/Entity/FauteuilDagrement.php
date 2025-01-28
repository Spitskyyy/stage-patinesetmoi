<?php

namespace App\Entity;

use App\Repository\FauteuilDagrementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FauteuilDagrementRepository::class)]
#[ORM\Table(name: 'tbl_fauteuil_d_agrement')]
class FauteuilDagrement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private array $pictures = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $usetxt = null;

    #[ORM\Column(nullable: true)]
    private ?float $width = null;

    #[ORM\Column(nullable: true)]
    private ?float $depth = null;

    #[ORM\Column(nullable: true)]
    private ?float $height = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $covering_or_complete_repair = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $materials = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fabric = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $finishes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPictures(): array
    {
        return $this->pictures;
    }

    public function setPictures(?array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function addPicture(string $picture): self
    {
        $this->pictures[] = $picture;

        return $this;
    }

    public function removePicture(string $picture): self
    {
        $this->pictures = array_filter($this->pictures, fn($p) => $p !== $picture);

        return $this;
    }

    public function getUsetxt(): ?string
    {
        return $this->usetxt;
    }

    public function setUsetxt(?string $usetxt): static
    {
        $this->usetxt = $usetxt;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(?float $depth): static
    {
        $this->depth = $depth;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getCoveringOrCompleteRepair(): ?string
    {
        return $this->covering_or_complete_repair;
    }

    public function setCoveringOrCompleteRepair(?string $covering_or_complete_repair): static
    {
        $this->covering_or_complete_repair = $covering_or_complete_repair;

        return $this;
    }

    public function getMaterials(): ?string
    {
        return $this->materials;
    }

    public function setMaterials(?string $materials): static
    {
        $this->materials = $materials;

        return $this;
    }

    public function getFabric(): ?string
    {
        return $this->fabric;
    }

    public function setFabric(?string $fabric): static
    {
        $this->fabric = $fabric;

        return $this;
    }

    public function getFinishes(): ?string
    {
        return $this->finishes;
    }

    public function setFinishes(?string $finishes): static
    {
        $this->finishes = $finishes;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): static
    {
        $this->time = $time;

        return $this;
    }
}
