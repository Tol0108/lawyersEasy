<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $nom_doc = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $sujet_doc = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $chemin = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "documents")]
    private ?Users $user = null;

    #[ORM\ManyToOne(targetEntity: Reservations::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservations $reservation = null;

    // Getters et setters ajoutés pour user
    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDoc(): ?string
    {
        return $this->nom_doc;
    }

    public function setNomDoc(string $nom_doc): static
    {
        $this->nom_doc = $nom_doc;

        return $this;
    }

    public function getSujetDoc(): ?string
    {
        return $this->sujet_doc;
    }

    public function setSujetDoc(?string $sujet_doc): static
    {
        $this->sujet_doc = $sujet_doc;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): self
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getReservation(): ?Reservations
    {
        return $this->reservation;
    }

    public function setReservation(?Reservations $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

}
