<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\DateTime]
    #[Assert\GreaterThan("now")]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $status = 'en attente';

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'reservations')]
    private ?Users $user = null;

    #[ORM\ManyToOne(targetEntity: Avocat::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Avocat $avocat = null;

    #[ORM\ManyToOne(targetEntity: Panier::class, inversedBy: 'reservations')]
    private ?Panier $panier = null;

    public function __construct()
    {
        $this->status = 'en attente';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getAvocat(): ?Avocat
    {
        return $this->avocat;
    }

    public function setAvocat(?Avocat $avocat): self
    {
        $this->avocat = $avocat;
        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }
}