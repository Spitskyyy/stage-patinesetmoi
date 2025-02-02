<?php

namespace App\Entity;

use App\Repository\PapierPeintRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PapierPeintRepository::class)]
#[ORM\Table(name: 'tbl_papier_peint')]
class PapierPeint
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
    private ?string $detail = null;
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }
    public function setCreatedAtValue()
{
    $this->createdAt = new \DateTime();
}

public function getCreatedAt(): ?\DateTimeInterface
{
    return $this->createdAt;
}
}
