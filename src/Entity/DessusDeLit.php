<?php

namespace App\Entity;

use App\Repository\DessusDeLitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DessusDeLitRepository::class)]
#[ORM\Table(name: 'tbl_dessus_de_lit')]
class DessusDeLit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $usetxt = null;

    #[ORM\Column(nullable: true)]
    private ?float $length = null;

    #[ORM\Column(nullable: true)]
    private ?float $width = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lining = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fabric = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bedspread_finishes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

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

    public function getUsetxt(): ?string
    {
        return $this->usetxt;
    }

    public function setUsetxt(?string $usetxt): static
    {
        $this->usetxt = $usetxt;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): static
    {
        $this->length = $length;

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

    public function getBedspreadFinishes(): ?string
    {
        return $this->bedspread_finishes;
    }

    public function setBedspreadFinishes(?string $bedspread_finishes): static
    {
        $this->bedspread_finishes = $bedspread_finishes;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
