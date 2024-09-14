<?php

namespace App\Entity;

use App\Repository\AvocatRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users;
use App\Entity\Specialite;

#[ORM\Entity(repositoryClass: AvocatRepository::class)]
class Avocat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\ManyToOne(targetEntity: Specialite::class)]
    private ?Specialite $specialite = null;

    #[ORM\OneToOne(targetEntity: Users::class, inversedBy: 'avocat', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        // Mettre à jour l'utilisateur pour refléter cette relation
        if ($user && $user->getAvocat() !== $this) {
        $user->setAvocat($this);
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
