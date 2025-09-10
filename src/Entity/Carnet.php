<?php

namespace App\Entity;

use App\Repository\CarnetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarnetRepository::class)]
class Carnet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $titre;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $dateCarnet;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoCarnet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateCarnet(): ?\DateTime
    {
        return $this->dateCarnet;
    }

    public function setDateCarnet(\DateTime $dateCarnet): static
    {
        $this->dateCarnet = $dateCarnet;

        return $this;
    }

    public function getPhotoCarnet(): ?string
    {
        return $this->photoCarnet;
    }

    public function setPhotoCarnet(?string $photoCarnet): static
    {
        $this->photoCarnet = $photoCarnet;

        return $this;
    }
}
