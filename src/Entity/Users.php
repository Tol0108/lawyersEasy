<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Role;


#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un utilisateur avec cet email existe déjà.')]
class Users implements UserInterface,
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $login = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 60)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    private ?string $prenom = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?int $telephone = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $langue = null;

    #[ORM\OneToMany(mappedBy: 'commentaire_user', targetEntity: Commentaire::class, cascade: ['persist', 'remove'])]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reservations::class)]
    private Collection $userreservation;

    const TYPE_CLIENT = 'client';
    const TYPE_AVOCAT = 'avocat';

    #[Assert\Choice(choices: [self::TYPE_CLIENT, self::TYPE_AVOCAT], message: 'Choisissez un type valide.')]
    private ?string $type = null;

    #[ORM\Column(length: 30)]
    private ?string $status = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->userreservation = new ArrayCollection();
        $this->userAvocat = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setCommentaireUser($this);
        }

        return $this;
    }

    public function removeommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommentaireUser() === $this) {
                $commentaire->setCommentaireUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getUserreservation(): Collection
    {
        return $this->userreservation;
    }

    public function addUserreservation(Reservations $userreservation): static
    {
        if (!$this->userreservation->contains($userreservation)) {
            $this->userreservation->add($userreservation);
            $userreservation->setUser($this);
        }

        return $this;
    }

    public function removeUserreservation(Reservations $userreservation): static
    {
        if ($this->userreservation->removeElement($userreservation)) {
            // set the owning side to null (unless already changed)
            if ($userreservation->getUser() === $this) {
                $userreservation->setUser(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = false;

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
        #[ORM\OneToMany(mappedBy: 'avocatUser', targetEntity: Avocat::class)]
        private Collection $userAvocat;

        #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class)]
        private Collection $user;

        /**
         * @return Collection<int, Avocat>
         */
        public function getUserAvocat(): Collection
        {
            return $this->userAvocat;
        }

        public function addUserAvocat(Avocat $userAvocat): static
        {
            if (!$this->userAvocat->contains($userAvocat)) {
                $this->userAvocat->add($userAvocat);
                $userAvocat->setAvocatUser($this);
            }

            return $this;
        }

        public function removeUserAvocat(Avocat $userAvocat): static
        {
            if ($this->userAvocat->removeElement($userAvocat)) {
                if ($userAvocat->getAvocatUser() === $this) {
                    $userAvocat->setAvocatUser(null);
                }
            }

            return $this;
        }

        public function getUsername(): string
        {
            return $this->email;
        }

        public function eraseCredentials()
        {
            // Si vous stockez des données sensibles temporaires, effacez-les ici
        }

        public function getUserIdentifier(): string
        {
            return $this->email;
        }

        public function getType(): ?string
        {
            return $this->type;
        }

        public function setType(string $type): self
        {
            $this->type = $type;

            return $this;
        }


        /**
         * @return Collection<int, Panier>
         */
        public function getUser(): Collection
        {
            return $this->user;
        }

        public function addUser(Panier $user): static
        {
            if (!$this->user->contains($user)) {
                $this->user->add($user);
                $user->setUser($this);
            }

            return $this;
        }

        public function removeUser(Panier $user): static
        {
            if ($this->user->removeElement($user)) {
                // set the owning side to null (unless already changed)
                if ($user->getUser() === $this) {
                    $user->setUser(null);
                }
            }

        return $this;
    }
    
}

