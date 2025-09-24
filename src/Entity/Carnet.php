<?php

namespace App\Entity;

use App\Repository\CarnetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?string $photo = null;

    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'carnet', orphanRemoval: true)]
    private Collection $invitations;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'carnet')]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'carnetsCrees')]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'carnetsAcces')]
    private Collection $usersAcces;

    public function __construct()
    {
        $this->invitations = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->usersAcces = new ArrayCollection();
    }

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setCarnet($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getCarnet() === $this) {
                $invitation->setCarnet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCarnet($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCarnet() === $this) {
                $post->setCarnet(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUsersAcces(): Collection
    {
        return $this->usersAcces;
    }

    public function addUserAcces(Utilisateur $userAcces): static
    {
        if (!$this->usersAcces->contains($userAcces)) {
            $this->usersAcces->add($userAcces);
            $userAcces->addCarnetAcces($this);
        }

        return $this;
    }

    public function removeUserAcces(Utilisateur $userAcces): static
    {
        if ($this->usersAcces->removeElement($userAcces)) {
            $userAcces->removeCarnetAcces($this);
        }

        return $this;
    }
}
