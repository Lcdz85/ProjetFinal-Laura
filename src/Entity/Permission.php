<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $admin = null;

    #[ORM\OneToOne(mappedBy: 'permission', cascade: ['persist', 'remove'])]
    private ?Carnet $carnet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getCarnet(): ?Carnet
    {
        return $this->carnet;
    }

    public function setCarnet(Carnet $carnet): static
    {
        // set the owning side of the relation if necessary
        if ($carnet->getPermission() !== $this) {
            $carnet->setPermission($this);
        }

        $this->carnet = $carnet;

        return $this;
    }
}
