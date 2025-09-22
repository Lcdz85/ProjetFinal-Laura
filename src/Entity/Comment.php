<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateComment = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'commentsCrees')]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'likedComments')]
    private Collection $usersLikes;

    public function __construct()
    {
        $this->usersLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateComment(): ?\DateTime
    {
        return $this->dateComment;
    }

    public function setDateComment(\DateTime $dateComment): static
    {
        $this->dateComment = $dateComment;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

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
    public function getUsersLikes(): Collection
    {
        return $this->usersLikes;
    }

    public function addUsersLike(Utilisateur $usersLike): static
    {
        if (!$this->usersLikes->contains($usersLike)) {
            $this->usersLikes->add($usersLike);
            $usersLike->addLikedComment($this);
        }

        return $this;
    }

    public function removeUsersLike(Utilisateur $usersLike): static
    {
        if ($this->usersLikes->removeElement($usersLike)) {
            $usersLike->removeLikedComment($this);
        }

        return $this;
    }
}
