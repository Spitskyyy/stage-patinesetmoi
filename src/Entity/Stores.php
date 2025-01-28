<?php

namespace App\Entity;

use App\Repository\StoresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoresRepository::class)]
#[ORM\Table(name: 'tbl_store')]
class Stores
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
    private ?float $height = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lining = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fabric = null;

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

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getLining(): ?string
    {
        return $this->lining;
    }

    public function setLining(?string $lining): static
    {
        $this->lining = $lining;

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
