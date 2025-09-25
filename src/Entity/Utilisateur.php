<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'utilisateur', cascade: ['persist'])]
    private Collection $invitations;

    /**
     * @var Collection<int, Carnet>
     */
    #[ORM\OneToMany(targetEntity: Carnet::class, mappedBy: 'utilisateur', cascade: ['persist'])]
    private Collection $carnetsCrees;

    /**
     * @var Collection<int, Carnet>
     */
    #[ORM\ManyToMany(targetEntity: Carnet::class, inversedBy: 'usersAcces', cascade: ['persist'])]
    private Collection $carnetsAcces;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'usersLikes', cascade: ['persist'])]
    private Collection $likedPosts;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'utilisateur', cascade: ['persist'])]
    private Collection $commentsCrees;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class, inversedBy: 'usersLikes', cascade: ['persist'])]
    private Collection $likedComments;

    public function __construct()
    {
        $this->invitations = new ArrayCollection();
        $this->carnetsCrees = new ArrayCollection();
        $this->carnetsAcces = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
        $this->commentsCrees = new ArrayCollection();
        $this->likedComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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
            $invitation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getUtilisateur() === $this) {
                $invitation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carnet>
     */
    public function getCarnetsCrees(): Collection
    {
        return $this->carnetsCrees;
    }

    public function addCarnetCree(Carnet $carnetCree): static
    {
        if (!$this->carnetsCrees->contains($carnetCree)) {
            $this->carnetsCrees->add($carnetCree);
            $carnetCree->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCarnetCree(Carnet $carnetCree): static
    {
        if ($this->carnetsCrees->removeElement($carnetCree)) {
            // set the owning side to null (unless already changed)
            if ($carnetCree->getUtilisateur() === $this) {
                $carnetCree->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carnet>
     */
    public function getCarnetsAcces(): Collection
    {
        return $this->carnetsAcces;
    }

    public function addCarnetAcces(Carnet $carnetAcces): static
    {
        if (!$this->carnetsAcces->contains($carnetAcces)) {
            $this->carnetsAcces->add($carnetAcces);
        }

        return $this;
    }

    public function removeCarnetAcces(Carnet $carnetAcces): static
    {
        $this->carnetsAcces->removeElement($carnetAcces);

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(Post $likedPost): static
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts->add($likedPost);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): static
    {
        $this->likedPosts->removeElement($likedPost);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentsCrees(): Collection
    {
        return $this->commentsCrees;
    }

    public function addCommentCree(Comment $commentCree): static
    {
        if (!$this->commentsCrees->contains($commentCree)) {
            $this->commentsCrees->add($commentCree);
            $commentCree->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentCree(Comment $commentCree): static
    {
        if ($this->commentsCrees->removeElement($commentCree)) {
            // set the owning side to null (unless already changed)
            if ($commentCree->getUtilisateur() === $this) {
                $commentCree->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getLikedComments(): Collection
    {
        return $this->likedComments;
    }

    public function addLikedComment(Comment $likedComment): static
    {
        if (!$this->likedComments->contains($likedComment)) {
            $this->likedComments->add($likedComment);
        }

        return $this;
    }

    public function removeLikedComment(Comment $likedComment): static
    {
        $this->likedComments->removeElement($likedComment);

        return $this;
    }
}
